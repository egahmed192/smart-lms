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
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_odoo_sync_status');
require_capability('local/odoo_sync:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/odoo_sync/status.php'));
$PAGE->set_title(get_string('sync_status', 'local_odoo_sync'));
$PAGE->set_heading(get_string('sync_status', 'local_odoo_sync'));

global $DB;

$retryid = optional_param('retry', 0, PARAM_INT);
if ($retryid > 0 && confirm_sesskey()) {
    $task = new \local_odoo_sync\task\sync_from_odoo();
    if ($task->retry_failure($retryid)) {
        redirect(new moodle_url('/local/odoo_sync/status.php'), get_string('retry_success', 'local_odoo_sync'), null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        redirect(new moodle_url('/local/odoo_sync/status.php'), get_string('retry_failed', 'local_odoo_sync'), null, \core\output\notification::NOTIFY_ERROR);
    }
}

$failures = $DB->get_records('local_odoo_sync_failures', null, 'timecreated DESC', '*', 0, 100);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('sync_status_heading', 'local_odoo_sync'));

if (empty($failures)) {
    echo '<p>' . get_string('no_failures', 'local_odoo_sync') . '</p>';
} else {
    $table = new \html_table();
    $table->head = [
        get_string('failure_time', 'local_odoo_sync'),
        get_string('failure_user', 'local_odoo_sync'),
        get_string('failure_odoo_id', 'local_odoo_sync'),
        get_string('failure_action', 'local_odoo_sync'),
        get_string('failure_error', 'local_odoo_sync'),
        '',
    ];
    foreach ($failures as $f) {
        $usertext = '';
        if (!empty($f->userid)) {
            $u = $DB->get_record('user', ['id' => $f->userid], 'id, username, firstname, lastname');
            $usertext = $u ? fullname($u) . ' (' . s($u->username) . ')' : (string) $f->userid;
        } else {
            $usertext = '-';
        }
        $retryurl = new moodle_url('/local/odoo_sync/status.php', ['retry' => $f->id, 'sesskey' => sesskey()]);
        $table->data[] = [
            userdate($f->timecreated),
            $usertext,
            (int) $f->odoo_id,
            s($f->action),
            s(trim($f->error_message ?? '')),
            $OUTPUT->action_link($retryurl, get_string('retry', 'local_odoo_sync'), null, ['class' => 'btn btn-sm btn-secondary']),
        ];
    }
    echo \html_writer::table($table);
}

echo '<p><a href="' . $CFG->wwwroot . '/admin/settings.php?section=local_odoo_sync" class="btn btn-link">' . get_string('odoo_sync_settings', 'local_odoo_sync') . '</a></p>';
echo $OUTPUT->footer();
