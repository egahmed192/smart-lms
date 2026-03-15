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

/**
 * Add Odoo sync student data to the user profile (view profile page).
 * Shows Odoo ID, grade (year), class (standard), license due date and status for synced students.
 * Visible to users with moodle/user:viewalldetails or local/odoo_sync:manage.
 *
 * @param \core_user\output\myprofile\tree $tree
 * @param \stdClass $user user whose profile is being viewed
 * @param bool $iscurrentuser
 * @param \stdClass|null $course
 */
function local_odoo_sync_myprofile_navigation(\core_user\output\myprofile\tree $tree, $user, $iscurrentuser, $course) {
    global $DB;

    $systemcontext = \context_system::instance();
    if (!has_capability('moodle/user:viewalldetails', $systemcontext) && !has_capability('local/odoo_sync:manage', $systemcontext)) {
        return;
    }

    $map = $DB->get_record('local_odoo_sync_map', ['userid' => $user->id, 'odoo_type' => 'student']);
    if (!$map) {
        return;
    }

    $lic = $DB->get_record('local_odoo_sync_lic', ['userid' => $user->id]);
    $yearname = '';
    if (!empty($map->year_apply_for_id)) {
        $y = $DB->get_record('local_odoo_sync_year', ['odoo_id' => $map->year_apply_for_id]);
        $yearname = $y ? $y->display_name : ('ID ' . $map->year_apply_for_id);
    }
    $standardname = '';
    if (!empty($map->standard_id)) {
        $st = $DB->get_record('local_odoo_sync_standard', ['odoo_id' => $map->standard_id]);
        $standardname = $st ? $st->display_name : ('ID ' . $map->standard_id);
    }

    $category = new \core_user\output\myprofile\category('local_odoo_sync', get_string('profile_category_odoo', 'local_odoo_sync'), 'administration');
    $tree->add_category($category);

    $node = new \core_user\output\myprofile\node('local_odoo_sync', 'odoo_id', get_string('profile_odoo_id', 'local_odoo_sync'), null, null, (string) $map->odoo_id);
    $tree->add_node($node);

    if ($yearname !== '') {
        $node = new \core_user\output\myprofile\node('local_odoo_sync', 'odoo_year', get_string('profile_year', 'local_odoo_sync'), null, null, $yearname);
        $tree->add_node($node);
    }
    if ($standardname !== '') {
        $node = new \core_user\output\myprofile\node('local_odoo_sync', 'odoo_standard', get_string('profile_standard', 'local_odoo_sync'), null, null, $standardname);
        $tree->add_node($node);
    }

    if ($lic) {
        $duetext = $lic->license_expiry ? userdate($lic->license_expiry, get_string('strftimedatefullshort')) : '-';
        $node = new \core_user\output\myprofile\node('local_odoo_sync', 'license_due', get_string('profile_license_due', 'local_odoo_sync'), null, null, $duetext);
        $tree->add_node($node);

        $statuskey = 'profile_license_other';
        if ($lic->license_status === 'active') {
            $statuskey = 'profile_license_active';
        } elseif ($lic->license_status === 'expired') {
            $statuskey = 'profile_license_expired';
        }
        $node = new \core_user\output\myprofile\node('local_odoo_sync', 'license_status', get_string('profile_license_status', 'local_odoo_sync'), null, null, get_string($statuskey, 'local_odoo_sync'));
        $tree->add_node($node);
    }
}
