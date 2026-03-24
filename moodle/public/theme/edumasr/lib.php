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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Edumasr Academy theme library functions.
 *
 * @package    theme_edumasr
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Get sidebar navigation items for Edumasr layout.
 *
 * @return array Array of nav items with text, url, icon, isactive
 */
function theme_edumasr_get_sidenav_items() {
    global $PAGE;

    $items = [];
    if (isloggedin() && !isguestuser()) {
        $items[] = [
            'text' => get_string('myhome', 'moodle'),
            'url' => (new moodle_url('/my/'))->out(false),
            'icon' => 'fa-th-large',
            'isactive' => ($PAGE->pagetype === 'my-index'),
        ];
        $items[] = [
            'text' => get_string('mycourses', 'moodle'),
            'url' => (new moodle_url('/local/edumasrdashboard/courses.php'))->out(false),
            'icon' => 'fa-graduation-cap',
            'isactive' => (strpos($PAGE->url->get_path(), '/my/courses') !== false || strpos($PAGE->url->get_path(), '/local/edumasrdashboard/courses') !== false),
        ];
        $items[] = [
            'text' => get_string('calendar', 'calendar'),
            'url' => (new moodle_url('/calendar/view.php', ['view' => 'month']))->out(false),
            'icon' => 'fa-calendar',
            'isactive' => (strpos($PAGE->url->get_path(), '/calendar/') !== false),
        ];
        $items[] = [
            'text' => get_string('messages', 'message'),
            'url' => (new moodle_url('/local/edumasrdashboard/messages.php'))->out(false),
            'icon' => 'fa-comments',
            'isactive' => (strpos($PAGE->url->get_path(), '/message/') !== false || strpos($PAGE->url->get_path(), '/local/edumasrdashboard/messages') !== false),
        ];
        $items[] = [
            'text' => get_string('files'),
            'url' => (new moodle_url('/repository/index.php'))->out(false),
            'icon' => 'fa-folder-open',
            'isactive' => false,
        ];
        $items[] = [
            'text' => get_string('settings'),
            'url' => (new moodle_url('/user/preferences.php'))->out(false),
            'icon' => 'fa-cog',
            'isactive' => (strpos($PAGE->url->get_path(), '/user/preferences') !== false),
        ];
    } else {
        $items[] = [
            'text' => get_string('home'),
            'url' => (new moodle_url('/'))->out(false),
            'icon' => 'fa-home',
            'isactive' => false,
        ];
    }
    return $items;
}

/**
 * Get extra SCSS for the theme.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_edumasr_get_extra_scss($theme) {
    $extra = theme_boost_get_extra_scss($theme);
    // Append dashboard layout CSS (ensures it loads even if preset import fails).
    $dashboardpath = $theme->dir . '/scss/edumasr-dashboard.scss';
    if (file_exists($dashboardpath)) {
        $extra .= "\n" . file_get_contents($dashboardpath);
    }
    return $extra;
}

/**
 * Get SCSS to prepend - maps admin settings to Bootstrap variables.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_edumasr_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        'primarycolor' => ['primary'],
        'accentcolor' => ['secondary'],
        'borderradius' => ['border-radius', 'border-radius-lg'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (!empty($value) || $value === '0') {
            foreach ((array) $targets as $target) {
                $scss .= '$' . $target . ': ' . $value . ";\n";
            }
        }
    }

    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss . theme_boost_get_pre_scss($theme);
}

/**
 * Returns the main SCSS content - uses our custom preset.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_edumasr_get_main_scss_content($theme) {
    global $CFG;

    $presetpath = $theme->dir . '/scss/preset/edumasr.scss';
    if (file_exists($presetpath)) {
        return file_get_contents($presetpath);
    }

    return file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
}

