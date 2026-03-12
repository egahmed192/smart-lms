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

admin_externalpage_setup('local_message_audit');
require_capability('local/message_audit:view_logs', context_system::instance());

$PAGE->set_url(new moodle_url('/local/message_audit/keywords.php'));
$PAGE->set_title(get_string('keyword_rules', 'local_message_audit'));
$PAGE->set_heading(get_string('keyword_rules', 'local_message_audit'));

global $DB;

$delete = optional_param('delete', 0, PARAM_INT);
if ($delete && confirm_sesskey()) {
    $DB->delete_records('local_message_audit_keywords', ['id' => $delete]);
    redirect(new moodle_url('/local/message_audit/keywords.php'), get_string('deleted', 'core'), null, \core\output\notification::NOTIFY_SUCCESS);
}

$keywords = $DB->get_records('local_message_audit_keywords', null, 'id ASC');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('keyword_rules', 'local_message_audit'));
echo html_writer::link(new moodle_url('/local/message_audit/index.php'), get_string('message_log', 'local_message_audit')) . ' | ';
echo html_writer::link(new moodle_url('/local/message_audit/keywords_edit.php'), get_string('add_keyword', 'local_message_audit')) . '<br><br>';

$table = new html_table();
$table->head = ['Pattern', 'Severity', 'Action', 'Actions'];
$table->data = [];
foreach ($keywords as $kw) {
    $delurl = new moodle_url('/local/message_audit/keywords.php', ['delete' => $kw->id, 'sesskey' => sesskey()]);
    $editurl = new moodle_url('/local/message_audit/keywords_edit.php', ['id' => $kw->id]);
    $table->data[] = [
        s($kw->pattern),
        s($kw->severity),
        s($kw->action),
        html_writer::link($editurl, get_string('edit')) . ' | ' . html_writer::link($delurl, get_string('delete')),
    ];
}
if (!empty($table->data)) {
    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('no_keywords', 'local_message_audit'), 'info');
}
echo $OUTPUT->footer();
