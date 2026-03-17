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
 * Inject CSS for parent users to hide all Moodle primary navigation,
 * course-index drawer, block drawer, and their toggle buttons.
 * Parents should only see the site logo, their user menu, and the portal content.
 *
 * Moodle calls this callback automatically via standard_top_of_body_html().
 *
 * @return string HTML (a <style> block) or empty string for non-parents.
 */
function local_parent_portal_before_standard_top_of_body_html(): string {
    global $CFG, $USER, $DB;

    if (!isloggedin() || isguestuser()) {
        return '';
    }

    // Only treat the user as a parent for UI purposes if they explicitly have the
    // Parent role at system level (do NOT apply this to site admins automatically).
    $parentrole = $DB->get_record('role', ['shortname' => 'parent'], 'id', IGNORE_MISSING);
    if (!$parentrole) {
        return '';
    }
    $sysctx = context_system::instance();
    if (!user_has_role_assignment($USER->id, $parentrole->id, $sysctx->id)) {
        return '';
    }

    $dashboardurl = (new moodle_url('/local/parent_portal/dashboard.php'))->out(false);

    return '<style>
        /* Hide primary navigation links for parent role users */
        .primary-navigation { display: none !important; }
        /* Hide left (course-index) drawer and its toggle button */
        #theme_boost-drawers-courseindex,
        .drawer-toggler.drawer-left-toggle,
        .open-nav { display: none !important; }
        /* Hide right (blocks) drawer and its toggle button */
        #theme_boost-drawers-blocks,
        .drawer-toggler.drawer-right-toggle { display: none !important; }
        /* Remove the extra left padding the drawers normally push the page with */
        body.drawers #page { margin-left: 0 !important; padding-left: 0 !important; }
        /* Hide the secondary (course-level) navigation bar */
        .secondary-navigation { display: none !important; }
        /* Hide the mobile nav toggler */
' . "        .navbar-toggler { display: none !important; }\n" . '    </style>
    <script>
        // Ensure the site logo and any primary home link send parents
        // to the parent dashboard instead of /my/.
        document.addEventListener("DOMContentLoaded", function() {
            var homelink = document.querySelector(".navbar-brand");
            if (homelink) {
                homelink.setAttribute("href", ' . json_encode($dashboardurl) . ');
            }
            // Also adjust the mobile primary drawer home link if present.
            var mobileHome = document.querySelector("#theme_boost-primary-drawer a[href*=\"/my/\"]");
            if (mobileHome) {
                mobileHome.setAttribute("href", ' . json_encode($dashboardurl) . ');
            }
        });
    </script>
';
}

/**
 * Get all student user IDs linked to a parent.
 *
 * @param int $parentuserid Moodle user id of the parent
 * @return int[] list of student user ids
 */
function local_parent_portal_get_students_for_parent(int $parentuserid): array {
    global $DB;
    $records = $DB->get_records('local_parent_portal_rel', ['parent_userid' => $parentuserid, 'active' => 1], '', 'student_userid');
    return array_map('intval', array_keys($records));
}

/**
 * Check whether user X is a parent of student Y.
 *
 * @param int $parentuserid Moodle user id of the parent
 * @param int $studentuserid Moodle user id of the student
 * @return bool
 */
function local_parent_portal_is_parent_of(int $parentuserid, int $studentuserid): bool {
    global $DB;
    return $DB->record_exists('local_parent_portal_rel', [
        'parent_userid' => $parentuserid,
        'student_userid' => $studentuserid,
        'active' => 1,
    ]);
}
