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

require_once($CFG->dirroot . '/theme/boost/lib.php');

/**
 * Get the main SCSS content (inherit from boost).
 *
 * @param theme_config $theme
 * @return string
 */
function theme_school_get_main_scss_content($theme) {
    return theme_boost_get_main_scss_content($theme);
}

/**
 * Get role-based quick links for the school theme (for use in blocks or custom output).
 * Returns array of ['url' => moodle_url, 'text' => string] for the current user's role.
 *
 * @return array
 */
function theme_school_get_role_links() {
    global $USER;
    $links = [];
    if (isloggedin() && !isguestuser()) {
        if (has_capability('local/parent_portal:view_child_data', context_system::instance())) {
            $links[] = ['url' => new moodle_url('/local/parent_portal/dashboard.php'), 'text' => get_string('parent_dashboard', 'local_parent_portal')];
        }
        if (has_capability('local/assessments:manage_public', context_system::instance()) || has_capability('local/assessments:manage_secretive', context_system::instance())) {
            $links[] = ['url' => new moodle_url('/my'), 'text' => get_string('mycourses')];
        }
    }
    return $links;
}
