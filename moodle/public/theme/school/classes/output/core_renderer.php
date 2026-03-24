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

    /**
     * Add a body class when low bandwidth mode is enabled.
     *
     * @param array $additionalclasses
     * @return string
     */
    public function body_css_classes($additionalclasses = []) {
        if (!is_array($additionalclasses)) {
            $additionalclasses = explode(' ', (string)$additionalclasses);
        }

        if (isloggedin() && !isguestuser() && (int)get_user_preferences('theme_school_lowbandwidth', 0) === 1) {
            $additionalclasses[] = 'theme-school-lowbandwidth';
        }

        return parent::body_css_classes($additionalclasses);
    }

    /**
     * Add a small footer toggle for low bandwidth mode.
     *
     * @return string
     */
    public function standard_footer_html() {
        global $PAGE;

        $html = parent::standard_footer_html();

        if (!isloggedin() || isguestuser()) {
            return $html;
        }

        $enabled = (int)get_user_preferences('theme_school_lowbandwidth', 0) === 1;
        $label = $enabled ? get_string('lowbandwidth:on', 'theme_school') : get_string('lowbandwidth:off', 'theme_school');
        $returnurl = $PAGE->url->out(false);
        $toggleurl = new \moodle_url('/theme/school/lowbandwidth.php', [
            'sesskey' => sesskey(),
            'returnurl' => $returnurl,
        ]);

        $toggle = \html_writer::link($toggleurl, s($label), [
            'class' => 'small text-muted',
            'title' => get_string('lowbandwidth:toggle', 'theme_school'),
        ]);

        return $html . \html_writer::div($toggle, 'mt-2 text-center');
    }
}

