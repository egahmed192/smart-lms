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
    $settings = new admin_settingpage('local_odoo_sync', get_string('pluginname', 'local_odoo_sync'));
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
    $ADMIN->add('localplugins', $settings);
}
