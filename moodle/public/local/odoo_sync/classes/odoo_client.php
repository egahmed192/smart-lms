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
global $CFG;
require_once($CFG->libdir . '/filelib.php');

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
        // Avoid HTTP 0 on Windows/SSL so we get real Set-Cookie and session is accepted on next request.
        $this->curl->setopt(['CURLOPT_SSL_VERIFYPEER' => 0, 'CURLOPT_SSL_VERIFYHOST' => 0]);
    }

    /**
     * Call JSON-RPC endpoint.
     *
     * @param string $path e.g. /api/lms/login
     * @param array $params
     * @param int $id
     * @param bool $isretry Internal: true when retrying after session expiry
     * @return array decoded result
     * @throws \moodle_exception on API error
     */
    public function call(string $path, array $params = [], int $id = 1, bool $isretry = false): array {
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
        $httpcode = (int) $this->curl->get_info()->http_code;
        $decoded = json_decode($response, true);
        $validjson = (json_last_error() === JSON_ERROR_NONE);

        // JSON-RPC top-level error (e.g. "Odoo Session Expired"). Retry once after re-login.
        if ($validjson && !empty($decoded['error'])) {
            $err = $decoded['error'];
            $msg = is_string($err) ? $err : ($err['message'] ?? 'Unknown API error');
            if (!$isretry && $path !== '/api/lms/login' &&
                (stripos($msg, 'session') !== false && stripos($msg, 'expired') !== false)) {
                $this->sessioncookie = null;
                $this->login();
                return $this->call($path, $params, $id, true);
            }
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', $msg);
        }

        $hasresult = ($validjson && isset($decoded['result']));
        $result = $hasresult ? $decoded['result'] : null;

        // If body is valid JSON with a successful result, accept it even when http_code is 0
        // (e.g. some Windows/SSL setups report 0 despite a successful response).
        if ($hasresult && $result !== null) {
            if (!empty($result['success']) || !isset($result['error'])) {
                // Session must come from Set-Cookie only. The JSON result.data.session_id is NOT
                // the cookie value the server expects for subsequent requests (verified against API).
                $rawheaders = $this->curl->get_raw_response();
                $headerblock = is_array($rawheaders) ? implode("\n", $rawheaders) : (string) $rawheaders;
                if (preg_match('/Set-Cookie:\s*(session_id=[^;\r\n]+)/i', $headerblock, $m)) {
                    $this->sessioncookie = trim($m[1]);
                }
                return $result;
            }
            $detail = $result['message'] ?? $result['error'] ?? 'Unknown API error';
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', $detail);
        }

        if ($httpcode < 200 || $httpcode >= 300) {
            $detail = 'HTTP ' . $httpcode . ' ' . (trim($response) !== '' ? ': ' . substr(trim($response), 0, 200) : '');
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', $detail);
        }
        if (!$validjson) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', 'Invalid JSON: ' . json_last_error_msg());
        }
        if (!isset($decoded['result'])) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '', 'No result in response');
        }
        return $decoded['result'];
    }

    /**
     * Login and store session. The server must send Set-Cookie (session_id=...) for subsequent requests to work.
     */
    public function login(): void {
        $result = $this->call('/api/lms/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);
        if (empty($result['success'])) {
            throw new \moodle_exception('odoo_login_failed', 'local_odoo_sync');
        }
        if ($this->sessioncookie === null) {
            throw new \moodle_exception('odoo_apierror', 'local_odoo_sync', '',
                'Login succeeded but no session cookie was received. Check SSL/connectivity so response headers are available.');
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
     * Fetch all students by querying with multiple name prefixes and merging by id.
     * API returns up to 100 per search and does not support offset/limit; this works around it.
     *
     * @return array list of student records keyed by id (same shape as student_search['students'])
     */
    public function student_search_all(): array {
        $byid = [];
        $prefixes = [
            'ا', 'أ', 'آ', 'إ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ',
            'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي',
        ];
        foreach ($prefixes as $letter) {
            $result = $this->student_search(['name' => $letter]);
            foreach ($result['students'] ?? [] as $s) {
                $id = (int) ($s['id'] ?? 0);
                if ($id) {
                    $byid[$id] = $s;
                }
            }
        }
        return $byid;
    }

    /**
     * Fetch all parents by querying with multiple name prefixes and merging by id.
     *
     * @return array list of parent records keyed by id
     */
    public function parent_search_all(): array {
        $byid = [];
        $prefixes = [
            'ا', 'أ', 'آ', 'إ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ',
            'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي',
        ];
        foreach ($prefixes as $letter) {
            $result = $this->parent_search(['name' => $letter]);
            foreach ($result['parents'] ?? [] as $p) {
                $id = (int) ($p['id'] ?? 0);
                if ($id) {
                    $byid[$id] = $p;
                }
            }
        }
        return $byid;
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
