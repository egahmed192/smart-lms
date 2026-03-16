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

class observer {

    /**
     * On user login:
     *  - If user holds the 'parent' role at system context → send them straight to the parent portal.
     *  - Otherwise check Odoo license validity and block if expired.
     *
     * @param \core\event\user_loggedin $event
     */
    public static function user_loggedin(\core\event\user_loggedin $event): void {
        global $DB;
        require_once(__DIR__ . '/../lib.php');

        $userid = $event->userid;

        // Redirect parents directly to their portal dashboard.
        $parentrole = $DB->get_record('role', ['shortname' => 'parent'], 'id', IGNORE_MISSING);
        if ($parentrole) {
            $sysctx = \context_system::instance();
            if (user_has_role_assignment($userid, $parentrole->id, $sysctx->id)) {
                redirect(new \moodle_url('/local/parent_portal/dashboard.php'));
            }
        }

        // For all other Odoo-managed accounts, enforce license expiry.
        if (local_odoo_sync_is_license_valid($userid)) {
            return;
        }
        local_odoo_sync_license_audit_log($userid, 'access_blocked');
        redirect(new \moodle_url('/local/odoo_sync/blocked.php'));
    }

    /**
     * When a course is deleted, remove its Odoo course mapping so no orphan rows remain.
     *
     * @param \core\event\course_deleted $event
     */
    public static function course_deleted(\core\event\course_deleted $event): void {
        global $DB;
        $DB->delete_records('local_odoo_sync_course_map', ['courseid' => $event->objectid]);
    }
}
