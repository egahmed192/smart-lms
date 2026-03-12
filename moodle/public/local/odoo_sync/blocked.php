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

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url(new moodle_url('/local/odoo_sync/blocked.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('access_blocked', 'local_odoo_sync'));
$PAGE->set_heading(get_string('access_blocked', 'local_odoo_sync'));

// If not logged in, send to login.
if (!isloggedin() || isguestuser()) {
    redirect(get_login_url());
}

// If license is actually valid (e.g. just updated), allow through.
if (local_odoo_sync_is_license_valid($USER->id)) {
    redirect(new moodle_url('/'));
}

echo $OUTPUT->header();
echo $OUTPUT->notification(get_string('access_blocked_message', 'local_odoo_sync'), 'error');
echo html_writer::link(new moodle_url('/login/logout.php', ['sesskey' => sesskey()]), get_string('logout'), ['class' => 'btn btn-primary']);
echo $OUTPUT->footer();
