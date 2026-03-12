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

defined('MOODLE_INTERNAL') || die();

/**
 * Check if a user's license is valid (not expired).
 * Returns true if no license record or status is active and expiry is in the future.
 *
 * @param int $userid
 * @return bool
 */
function local_odoo_sync_is_license_valid(int $userid): bool {
    global $DB;
    $rec = $DB->get_record('local_odoo_sync_lic', ['userid' => $userid]);
    if (!$rec) {
        return true; // No license record = not managed by Odoo, allow.
    }
    if ($rec->license_status !== 'active') {
        return false;
    }
    if (empty($rec->license_expiry)) {
        return true;
    }
    return $rec->license_expiry >= time();
}

/**
 * Log license enforcement action for audit.
 *
 * @param int $userid
 * @param string $action
 */
function local_odoo_sync_license_audit_log(int $userid, string $action): void {
    global $DB;
    $DB->insert_record('local_odoo_sync_license_audit', (object)[
        'userid' => $userid,
        'action' => $action,
        'timecreated' => time(),
    ]);
}
