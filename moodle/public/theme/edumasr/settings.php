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
 * Edumasr Academy theme settings - Admin only.
 *
 * @package    theme_edumasr
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettingedumasr', get_string('configtitle', 'theme_edumasr'));

    $page = new admin_settingpage('theme_edumasr_general', get_string('generalsettings', 'theme_boost'));

    // Primary color (orange in design).
    $name = 'theme_edumasr/primarycolor';
    $title = get_string('primarycolor', 'theme_edumasr');
    $description = get_string('primarycolor_desc', 'theme_edumasr');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#ff7518');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Accent color (yellow in design).
    $name = 'theme_edumasr/accentcolor';
    $title = get_string('accentcolor', 'theme_edumasr');
    $description = get_string('accentcolor_desc', 'theme_edumasr');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#f0ad4e');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Border radius for cards and buttons.
    $name = 'theme_edumasr/borderradius';
    $title = get_string('borderradius', 'theme_edumasr');
    $description = get_string('borderradius_desc', 'theme_edumasr');
    $setting = new admin_setting_configtext($name, $title, $description, '.5rem', PARAM_TEXT);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Brand color (logo/accent).
    $name = 'theme_edumasr/brandcolor';
    $title = get_string('brandcolor', 'theme_boost');
    $description = get_string('brandcolor_desc', 'theme_boost');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#ff7518');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Advanced settings.
    $page = new admin_settingpage('theme_edumasr_advanced', get_string('advancedsettings', 'theme_boost'));

    $setting = new admin_setting_scsscode('theme_edumasr/scsspre',
        get_string('rawscsspre', 'theme_boost'), get_string('rawscsspre_desc', 'theme_boost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_scsscode('theme_edumasr/scss',
        get_string('rawscss', 'theme_boost'), get_string('rawscss_desc', 'theme_boost'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}

