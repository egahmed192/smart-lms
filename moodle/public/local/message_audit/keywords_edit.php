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
require_once($CFG->dirroot . '/local/message_audit/form/keyword_form.php');

admin_externalpage_setup('local_message_audit');
require_capability('local/message_audit:view_logs', context_system::instance());

$id = optional_param('id', 0, PARAM_INT);
$PAGE->set_url(new moodle_url('/local/message_audit/keywords_edit.php', array('id' => $id)));
$PAGE->set_title($id ? get_string('edit') : get_string('add_keyword', 'local_message_audit'));
$PAGE->set_heading($id ? get_string('edit') : get_string('add_keyword', 'local_message_audit'));

global $DB;
$keyword = $id ? $DB->get_record('local_message_audit_keywords', array('id' => $id)) : null;

$mform = new local_message_audit_keyword_form(null, array('keyword' => $keyword));
if ($keyword) {
    $mform->set_data($keyword);
}
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/message_audit/keywords.php'));
}
if ($data = $mform->get_data()) {
    $record = (object)[
        'pattern' => $data->pattern,
        'severity' => $data->severity,
        'action' => $data->action,
        'timemodified' => time(),
    ];
    if ($keyword) {
        $record->id = $keyword->id;
        $DB->update_record('local_message_audit_keywords', $record);
    } else {
        $DB->insert_record('local_message_audit_keywords', $record);
    }
    redirect(new moodle_url('/local/message_audit/keywords.php'), get_string('changessaved'));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
