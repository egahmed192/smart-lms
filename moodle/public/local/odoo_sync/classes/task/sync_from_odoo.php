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

namespace local_odoo_sync\task;

defined('MOODLE_INTERNAL') || die();

class sync_from_odoo extends \core\task\scheduled_task {

    public function get_name(): string {
        return get_string('task_sync_from_odoo', 'local_odoo_sync');
    }

    public function execute(): void {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/user/lib.php');
        require_once($CFG->dirroot . '/local/parent_portal/lib.php');

        $apiurl = get_config('local_odoo_sync', 'apiurl');
        $apiuser = get_config('local_odoo_sync', 'apiuser');
        $apipassword = get_config('local_odoo_sync', 'apipassword');
        if (empty($apiurl) || empty($apiuser) || empty($apipassword)) {
            mtrace('Odoo sync: API not configured. Skipping.');
            return;
        }

        try {
            $client = new \local_odoo_sync\odoo_client($apiurl, $apiuser, $apipassword);
            $client->login();
        } catch (\Throwable $e) {
            mtrace('Odoo sync: Login failed: ' . $e->getMessage());
            return;
        }

        $studentrole = $DB->get_record('role', ['shortname' => 'student']);
        $parentrole = $DB->get_record('role', ['shortname' => 'parent']);
        if (!$studentrole) {
            $studentrole = $DB->get_record('role', ['shortname' => 'student'], IGNORE_MULTIPLE) ?: null;
        }
        $sysctx = \context_system::instance();
        $manualplugin = enrol_get_plugin('manual');
        if (!$manualplugin) {
            mtrace('Manual enrolment plugin not found.');
            return;
        }

        // Fetch all students (paginate by name search).
        $offset = 0;
        $limit = 100;
        $allstudents = [];
        do {
            $result = $client->student_search(['name' => ' ']);
            $students = $result['students'] ?? [];
            foreach ($students as $s) {
                $allstudents[$s['id']] = $s;
            }
            if (count($students) < $limit) {
                break;
            }
            $offset += $limit;
        } while (count($students) === $limit);

        // If name search returns empty or small set, try without filter (some APIs allow).
        if (empty($allstudents)) {
            $result = $client->student_search(['name' => 'ا']);
            foreach ($result['students'] ?? [] as $s) {
                $allstudents[$s['id']] = $s;
            }
        }

        // Sync parents first so we can link by guardian_national_id.
        $allparents = [];
        $result = $client->parent_search(['name' => ' ']);
        foreach ($result['parents'] ?? [] as $p) {
            $allparents[$p['id']] = $p;
        }
        if (empty($allparents)) {
            $result = $client->parent_search(['name' => 'ا']);
            foreach ($result['parents'] ?? [] as $p) {
                $allparents[$p['id']] = $p;
            }
        }
        foreach ($allparents as $odooParent) {
            try {
                $this->sync_one_parent($odooParent, $client, $parentrole, $sysctx);
            } catch (\Throwable $e) {
                $this->log_failure($DB, null, (int)($odooParent['id'] ?? 0), 'parent', 'create_user', $e->getMessage());
                mtrace('Odoo sync: Parent sync failed for ' . ($odooParent['id'] ?? '') . ': ' . $e->getMessage());
            }
        }

        foreach ($allstudents as $odooStudent) {
            $user = null;
            try {
                $user = $this->sync_one_student($odooStudent, $client, $studentrole, $sysctx, $manualplugin);
            } catch (\Throwable $e) {
                $this->log_failure($DB, null, (int)($odooStudent['id'] ?? 0), 'student', 'create_user', $e->getMessage());
                mtrace('Odoo sync: Student sync failed for ' . ($odooStudent['id'] ?? '') . ': ' . $e->getMessage());
            }
            try {
                $this->link_student_to_parent($user ? $user->id : null, $odooStudent['guardian_national_id'] ?? '');
            } catch (\Throwable $e) {
                $this->log_failure($DB, $user ? $user->id : null, (int)($odooStudent['id'] ?? 0), 'student', 'link_parent', $e->getMessage());
                mtrace('Odoo sync: Link parent failed: ' . $e->getMessage());
            }
        }
    }

    private function log_failure($DB, ?int $userid, int $odooid, string $odootype, string $action, string $errmsg): void {
        $DB->insert_record('local_odoo_sync_failures', (object)[
            'userid' => $userid,
            'odoo_id' => $odooid ?: null,
            'odoo_type' => $odootype,
            'action' => $action,
            'error_message' => $errmsg,
            'timecreated' => time(),
        ]);
    }

    private function get_moodle_userid_for_odoo_student(int $odoostudentid): ?int {
        global $DB;
        $m = $DB->get_record('local_odoo_sync_map', ['odoo_id' => $odoostudentid, 'odoo_type' => 'student']);
        return $m ? (int) $m->userid : null;
    }

    private function link_student_to_parent(?int $studentuserid, string $guardiannationalid): void {
        global $DB;
        if ($studentuserid === null || $guardiannationalid === '') {
            return;
        }
        $parent = $DB->get_record_sql(
            "SELECT u.id FROM {user} u
              JOIN {local_odoo_sync_map} m ON m.userid = u.id
              WHERE m.odoo_type = 'parent' AND u.idnumber = ?",
            [$guardiannationalid]
        );
        if (!$parent) {
            return;
        }
        if ($DB->record_exists('local_parent_portal_rel', [
            'parent_userid' => $parent->id,
            'student_userid' => $studentuserid,
            'active' => 1,
        ])) {
            return;
        }
        $DB->insert_record('local_parent_portal_rel', (object)[
            'parent_userid' => $parent->id,
            'student_userid' => $studentuserid,
            'active' => 1,
            'timecreated' => time(),
            'source' => 'odoo',
        ]);
    }

    /**
     * Retry syncing one failure record (from status.php). Fetches the entity from Odoo and runs sync.
     *
     * @param int $failureid local_odoo_sync_failures.id
     * @return bool success
     */
    public function retry_failure(int $failureid): bool {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/user/lib.php');
        $fail = $DB->get_record('local_odoo_sync_failures', ['id' => $failureid]);
        if (!$fail || !$fail->odoo_id || !$fail->odoo_type) {
            return false;
        }
        $apiurl = get_config('local_odoo_sync', 'apiurl');
        $apiuser = get_config('local_odoo_sync', 'apiuser');
        $apipassword = get_config('local_odoo_sync', 'apipassword');
        if (empty($apiurl) || empty($apiuser) || empty($apipassword)) {
            return false;
        }
        try {
            $client = new \local_odoo_sync\odoo_client($apiurl, $apiuser, $apipassword);
            $client->login();
        } catch (\Throwable $e) {
            return false;
        }
        $studentrole = $DB->get_record('role', ['shortname' => 'student']);
        $parentrole = $DB->get_record('role', ['shortname' => 'parent']);
        $sysctx = \context_system::instance();
        $manualplugin = enrol_get_plugin('manual');
        if ($fail->odoo_type === 'student') {
            $result = $client->student_search(['id' => $fail->odoo_id]);
            $students = $result['students'] ?? [];
            $one = reset($students);
            if (!$one || (int)($one['id'] ?? 0) !== (int)$fail->odoo_id) {
                return false;
            }
            try {
                $this->sync_one_student($one, $client, $studentrole, $sysctx, $manualplugin);
                $user = $DB->get_record('local_odoo_sync_map', ['odoo_id' => $fail->odoo_id, 'odoo_type' => 'student']);
                if ($user) {
                    $this->link_student_to_parent($user->userid, $one['guardian_national_id'] ?? '');
                }
            } catch (\Throwable $e) {
                $this->log_failure($DB, null, (int)$fail->odoo_id, 'student', $fail->action, $e->getMessage());
                return false;
            }
        } else {
            $result = $client->parent_search(['id' => $fail->odoo_id]);
            $parents = $result['parents'] ?? [];
            $one = reset($parents);
            if (!$one || (int)($one['id'] ?? 0) !== (int)$fail->odoo_id) {
                return false;
            }
            try {
                $this->sync_one_parent($one, $client, $parentrole, $sysctx);
            } catch (\Throwable $e) {
                $this->log_failure($DB, null, (int)$fail->odoo_id, 'parent', $fail->action, $e->getMessage());
                return false;
            }
        }
        $DB->delete_records('local_odoo_sync_failures', ['id' => $failureid]);
        return true;
    }

    private function sync_one_student(
        array $s,
        \local_odoo_sync\odoo_client $client,
        $studentrole,
        \context_system $sysctx,
        $manualplugin
    ): ?\stdClass {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        $odooid = (int) $s['id'];
        $yearid = isset($s['year_apply_for']['id']) ? (int) $s['year_apply_for']['id'] : 0;
        $standardid = isset($s['standard_id']['id']) ? (int) $s['standard_id']['id'] : 0;
        $fullname = $s['student_en_full_name'] ?? $s['student_full_name'] ?? '';
        $licenseDue = null;
        if (!empty($s['license_due_date'])) {
            $licenseDue = strtotime($s['license_due_date']);
            if ($licenseDue === false) {
                $licenseDue = null;
            }
        }

        $map = $DB->get_record('local_odoo_sync_map', ['odoo_id' => $odooid, 'odoo_type' => 'student']);
        $user = null;
        $isnew = false;
        if ($map) {
            $user = $DB->get_record('user', ['id' => $map->userid]);
        }
        if (!$user) {
            $user = new \stdClass();
            $user->auth = 'manual';
            $user->confirmed = 1;
            $user->deleted = 0;
            $user->username = 'odoo_student_' . $odooid;
            $user->email = $user->username . '@odoo.sync';
            $user->firstname = trim($fullname);
            $user->lastname = '';
            if (strpos($fullname, ' ') !== false) {
                $parts = explode(' ', $fullname, 2);
                $user->firstname = $parts[0];
                $user->lastname = $parts[1] ?? '';
            }
            $user->password = hash_internal_user_password(generate_password(12));
            $user->id = user_create_user($user);
            $isnew = true;
            $DB->insert_record('local_odoo_sync_map', (object)[
                'userid' => $user->id,
                'odoo_id' => $odooid,
                'odoo_type' => 'student',
                'timemodified' => time(),
                'year_apply_for_id' => $yearid,
                'standard_id' => $standardid,
            ]);
            if ($studentrole) {
                role_assign($studentrole->id, $user->id, $sysctx->id);
            }
            if (function_exists('local_assessments_get_secret_code')) {
                local_assessments_get_secret_code($user->id);
            }
            try {
                $client->student_update($odooid, $user->username, $user->password);
            } catch (\Throwable $e) {
                $this->log_failure($DB, $user->id, $odooid, 'student', 'update_credentials', $e->getMessage());
                mtrace("Could not push credentials for student {$odooid}: " . $e->getMessage());
            }
        } else {
            $oldyear = $map->year_apply_for_id ?? 0;
            $oldstandard = $map->standard_id ?? 0;
            $DB->update_record('local_odoo_sync_map', (object)[
                'id' => $map->id,
                'year_apply_for_id' => $yearid,
                'standard_id' => $standardid,
                'timemodified' => time(),
            ]);
            if ($oldyear != $yearid || $oldstandard != $standardid) {
                $this->unenrol_student_from_courses($user->id, $oldyear, $oldstandard, $manualplugin);
            }
        }

        $lic = $DB->get_record('local_odoo_sync_lic', ['userid' => $user->id]);
        $status = ($licenseDue !== null && $licenseDue < time()) ? 'expired' : 'active';
        if ($lic) {
            $DB->update_record('local_odoo_sync_lic', (object)[
                'id' => $lic->id,
                'license_expiry' => $licenseDue ?? 0,
                'license_status' => $status,
                'timemodified' => time(),
            ]);
        } else {
            $DB->insert_record('local_odoo_sync_lic', (object)[
                'userid' => $user->id,
                'license_expiry' => $licenseDue ?? 0,
                'license_status' => $status,
                'timemodified' => time(),
            ]);
        }

        $this->enrol_student_into_courses($user->id, $yearid, $standardid, $manualplugin);
        return $user;
    }

    private function enrol_student_into_courses(int $userid, int $yearid, int $standardid, $manualplugin): void {
        global $DB;
        if ($yearid === 0 && $standardid === 0) {
            return;
        }
        $courseids = $DB->get_fieldset_sql(
            "SELECT courseid FROM {local_odoo_sync_course_map} WHERE year_apply_for_id = ? AND standard_id = ?",
            [$yearid, $standardid]
        );
        foreach ($courseids as $courseid) {
            $instance = $DB->get_record('enrol', ['courseid' => $courseid, 'enrol' => 'manual']);
            if (!$instance) {
                continue;
            }
            $ue = $DB->get_record('user_enrolments', ['enrolid' => $instance->id, 'userid' => $userid]);
            if (!$ue) {
                $manualplugin->enrol_user($instance, $userid, null, time());
            }
        }
    }

    private function unenrol_student_from_courses(int $userid, int $yearid, int $standardid, $manualplugin): void {
        global $DB;
        if ($yearid === 0 && $standardid === 0) {
            return;
        }
        $courseids = $DB->get_fieldset_sql(
            "SELECT courseid FROM {local_odoo_sync_course_map} WHERE year_apply_for_id = ? AND standard_id = ?",
            [$yearid, $standardid]
        );
        foreach ($courseids as $courseid) {
            $instance = $DB->get_record('enrol', ['courseid' => $courseid, 'enrol' => 'manual']);
            if (!$instance) {
                continue;
            }
            $ue = $DB->get_record('user_enrolments', ['enrolid' => $instance->id, 'userid' => $userid]);
            if ($ue) {
                $manualplugin->unenrol_user($instance, $userid);
            }
        }
    }

    private function sync_one_parent(array $p, \local_odoo_sync\odoo_client $client, $parentrole, \context_system $sysctx): void {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        $odooid = (int) $p['id'];
        $map = $DB->get_record('local_odoo_sync_map', ['odoo_id' => $odooid, 'odoo_type' => 'parent']);
        $user = null;
        if ($map) {
            $user = $DB->get_record('user', ['id' => $map->userid]);
        }
        if (!$user) {
            $user = new \stdClass();
            $user->auth = 'manual';
            $user->confirmed = 1;
            $user->deleted = 0;
            $user->username = 'odoo_parent_' . $odooid;
            $user->email = !empty($p['email']) ? $p['email'] : ($user->username . '@odoo.sync');
            $user->idnumber = $p['identification_id'] ?? '';
            $name = $p['name'] ?? '';
            $user->firstname = trim($name);
            $user->lastname = '';
            if (strpos($name, ' ') !== false) {
                $parts = explode(' ', $name, 2);
                $user->firstname = $parts[0];
                $user->lastname = $parts[1] ?? '';
            }
            $user->password = hash_internal_user_password(generate_password(12));
            $user->id = user_create_user($user);
            $DB->insert_record('local_odoo_sync_map', (object)[
                'userid' => $user->id,
                'odoo_id' => $odooid,
                'odoo_type' => 'parent',
                'timemodified' => time(),
            ]);
            if ($parentrole) {
                role_assign($parentrole->id, $user->id, $sysctx->id);
            }
            try {
                $client->parent_update($odooid, $user->username, $user->password);
            } catch (\Throwable $e) {
                $this->log_failure($DB, $user->id, $odooid, 'parent', 'update_credentials', $e->getMessage());
                mtrace("Could not push credentials for parent {$odooid}: " . $e->getMessage());
            }
        }
    }

    }
