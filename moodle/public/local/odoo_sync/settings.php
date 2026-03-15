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

if ($hassiteconfig && has_capability('local/odoo_sync:manage', context_system::instance())) {
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_odoo_sync_status',
        get_string('sync_status', 'local_odoo_sync'),
        new moodle_url('/local/odoo_sync/status.php'),
        'local/odoo_sync:manage'
    ));
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_odoo_sync_course_map',
        get_string('course_map', 'local_odoo_sync'),
        new moodle_url('/local/odoo_sync/course_map.php'),
        'local/odoo_sync:manage'
    ));
    $settings = new admin_settingpage('local_odoo_sync', get_string('pluginname', 'local_odoo_sync'));
    $settings->add(new admin_setting_description(
        'local_odoo_sync_statuslink',
        '',
        '<a href="' . (new moodle_url('/local/odoo_sync/status.php'))->out(false) . '">' . get_string('sync_status', 'local_odoo_sync') . '</a>'
    ));
    $settings->add(new admin_setting_configtext(
        'local_odoo_sync/apiurl',
        get_string('odoo_api_url', 'local_odoo_sync'),
        get_string('odoo_api_url_help', 'local_odoo_sync'),
        'https://arafa.online',
        PARAM_URL
    ));
    $settings->add(new admin_setting_configtext(
        'local_odoo_sync/apiuser',
        get_string('odoo_api_user', 'local_odoo_sync'),
        '',
        '',
        PARAM_RAW
    ));
    $settings->add(new admin_setting_configpasswordunmask(
        'local_odoo_sync/apipassword',
        get_string('odoo_api_password', 'local_odoo_sync'),
        '',
        ''
    ));
    $settings->add(new admin_setting_configtext(
        'local_odoo_sync/license_expiry_days_before',
        get_string('license_expiry_days_before', 'local_odoo_sync'),
        get_string('license_expiry_days_before_help', 'local_odoo_sync'),
        '7',
        PARAM_INT
    ));
    $ADMIN->add('localplugins', $settings);
}
