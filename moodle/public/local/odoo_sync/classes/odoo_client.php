<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

namespace local_odoo_sync;

defined('MOODLE_INTERNAL') || die();

/**
 * Odoo LMS API client (JSON-RPC 2.0).
 * Base URL + /api/lms/login, /api/lms/student/search, etc.
 * Session cookie required after login.
 */
class odoo_client {

    /** @var string */
    protected $baseurl;
    /** @var string */
    protected $email;
    /** @var string */
    protected $password;
    /** @var \curl */
    protected $curl;
    /** @var string|null */
    protected $sessioncookie;

    public function __construct(string $baseurl, string $email, string $password) {
        $this->baseurl = rtrim($baseurl, '/');
        $this->email = $email;
        $this->password = $password;
        $this->curl = new \curl();
        $this->sessioncookie = null;
    }

    /**
     * Call JSON-RPC endpoint.
     *
     * @param string $path e.g. /api/lms/login
     * @param array $params
     * @param int $id
     * @return array decoded result
     * @throws \moodle_exception on API error
     */
    public function call(string $path, array $params = [], int $id = 1): array {
        $url = $this->baseurl . $path;
        $body = json_encode([
            'jsonrpc' => '2.0',
            'method' => 'call',
            'params' => $params,
            'id' => $id,
        ]);
        $this->curl->setHeader('Content-Type: application/json');
        if ($this->sessioncookie !== null) {
            $this->curl->setHeader('Cookie: ' . $this->sessioncookie);
        }
        $response = $this->curl->post($url, $body);
        $httpcode = $this->curl->get_info()->http_code;
        if ($httpcode < 200 || $httpcode >= 300) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', null, 'HTTP ' . $httpcode);
        }
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', null, 'Invalid JSON');
        }
        if (!isset($decoded['result'])) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', null, 'No result');
        }
        $result = $decoded['result'];
        if (!empty($result['success']) === false && isset($result['error'])) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', null, $result['message'] ?? $result['error']);
        }
        // Capture session cookie from response headers.
        $headers = $this->curl->get_info()->raw_response_headers ?? '';
        if (preg_match('/Set-Cookie:\s*([^\r\n]+)/i', $headers, $m)) {
            $this->sessioncookie = trim($m[1]);
        }
        return $result;
    }

    /**
     * Login and store session.
     */
    public function login(): void {
        $result = $this->call('/api/lms/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        if (empty($result['success']) || empty($result['data']['session_id'])) {
            throw new \moodle_exception('odoo_login_failed', 'local_odoo_sync');
        }
    }

    /**
     * Search students. At least one of id, name, national_id.
     *
     * @param array $params keys: id, name, national_id
     * @return array ['count' => int, 'students' => array]
     */
    public function student_search(array $params): array {
        $result = $this->call('/api/lms/student/search', $params, 2);
        return $result['data'] ?? ['count' => 0, 'students' => []];
    }

    /**
     * Update student LMS credentials in Odoo.
     *
     * @param int $id Odoo student id (or use national_id)
     * @param string|null $lms_username
     * @param string|null $lms_password
     */
    public function student_update(int $id, ?string $lms_username = null, ?string $lms_password = null): void {
        $params = ['id' => $id];
        if ($lms_username !== null) {
            $params['lms_username'] = $lms_username;
        }
        if ($lms_password !== null) {
            $params['lms_password'] = $lms_password;
        }
        $this->call('/api/lms/student/update', $params, 3);
    }

    /**
     * Search parents. At least one of id, name, national_id.
     *
     * @param array $params
     * @return array ['count' => int, 'parents' => array]
     */
    public function parent_search(array $params): array {
        $result = $this->call('/api/lms/parent/search', $params, 4);
        return $result['data'] ?? ['count' => 0, 'parents' => []];
    }

    /**
     * Update parent LMS credentials in Odoo.
     */
    public function parent_update(int $id, ?string $lms_username = null, ?string $lms_password = null): void {
        $params = ['id' => $id];
        if ($lms_username !== null) {
            $params['lms_username'] = $lms_username;
        }
        if ($lms_password !== null) {
            $params['lms_password'] = $lms_password;
        }
        $this->call('/api/lms/parent/update', $params, 5);
    }
}
