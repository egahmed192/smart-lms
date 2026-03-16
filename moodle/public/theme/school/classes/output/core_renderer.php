<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

namespace theme_school\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Custom core renderer for the school theme.
 *
 * Used to change the home URL for parents so that clicking the logo
 * and other "home" links sends them to the parent dashboard instead
 * of the standard dashboard.
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Return the default home URL for the current user.
     *
     * For parents (users with local/parent_portal:view_child_data at system
     * context) this is the parent dashboard. For everyone else, use the
     * standard behaviour from Boost.
     *
     * @return \moodle_url
     */
    public function get_home_url() {
        global $USER, $DB;

        if (isloggedin() && !isguestuser()) {
            // Treat someone as a "parent" only if they actually hold the Parent role
            // at system context, not just because they are a site admin with all caps.
            $parentrole = $DB->get_record('role', ['shortname' => 'parent'], 'id', IGNORE_MISSING);
            if ($parentrole) {
                $sysctx = \context_system::instance();
                if (user_has_role_assignment($USER->id, $parentrole->id, $sysctx->id)) {
                    return new \moodle_url('/local/parent_portal/dashboard.php');
                }
            }
        }

        return parent::get_home_url();
    }
}

