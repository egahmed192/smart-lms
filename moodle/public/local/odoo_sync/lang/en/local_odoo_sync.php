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

$string['pluginname'] = 'Odoo sync';
$string['access_blocked'] = 'Access blocked';
$string['access_blocked_message'] = 'Your access to the learning management system has been suspended because your license has expired. Please contact the school administration.';
$string['odoo_sync_settings'] = 'Odoo sync settings';
$string['odoo_api_url'] = 'Odoo API URL';
$string['odoo_api_url_help'] = 'Base URL for the Odoo LMS API (e.g. https://arafa.online)';
$string['odoo_api_user'] = 'API user (email)';
$string['odoo_api_password'] = 'API password';
$string['task_sync_from_odoo'] = 'Sync users and licenses from Odoo';
$string['odoo_apierror'] = 'Odoo API error: {$a}';
$string['odoo_login_failed'] = 'Odoo login failed.';
$string['task_push_grades'] = 'Push grades to Odoo';
$string['sync_status'] = 'Sync status';
$string['sync_status_heading'] = 'Recent sync failures';
$string['retry'] = 'Retry';
$string['failure_time'] = 'Time';
$string['failure_user'] = 'User';
$string['failure_odoo_id'] = 'Odoo ID';
$string['failure_action'] = 'Action';
$string['failure_error'] = 'Error';
$string['no_failures'] = 'No recent failures.';
$string['retry_success'] = 'Retry succeeded.';
$string['retry_failed'] = 'Retry failed.';
$string['task_license_expiry_reminder'] = 'License expiry reminder';
$string['license_expiry_days_before'] = 'Days before expiry to remind';
$string['license_expiry_days_before_help'] = 'Users with license expiring within this many days will receive a reminder (daily task).';
$string['license_expiry_reminder_subject'] = 'Your license is expiring soon';
$string['license_expiry_reminder_body'] = 'Your learning license expires on {$a}. Please contact the school to renew.';
$string['course_map'] = 'Class/Course mapping';
$string['course_map_heading'] = 'Map Moodle courses to Odoo class (grade + standard)';
$string['course_map_intro'] = 'Years and standards are synced from Odoo student data. Map each Moodle course to an Odoo year (grade) and standard (class). Students are then auto-enrolled in all courses mapped to their class.';
$string['odoo_year'] = 'Odoo year (grade)';
$string['odoo_standard'] = 'Odoo standard (class)';
$string['moodle_course'] = 'Moodle course';
$string['add_mapping'] = 'Add mapping';
$string['delete_mapping'] = 'Remove';
$string['mapping_added'] = 'Mapping added.';
$string['mapping_removed'] = 'Mapping removed.';
$string['no_years_standards'] = 'No years or standards yet. Run «Sync users and licenses from Odoo» to populate from student data.';
$string['synced_years'] = 'Synced years (grades)';
$string['synced_standards'] = 'Synced standards (classes)';
