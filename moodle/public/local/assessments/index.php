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
require_once(__DIR__ . '/lib.php');

$courseid = required_param('id', PARAM_INT);
$course = get_course($courseid);
require_login($course);
$PAGE->set_url(new moodle_url('/local/assessments/index.php', ['id' => $courseid]));
$PAGE->set_title(get_string('assessments', 'local_assessments'));
$PAGE->set_heading($course->fullname);

$context = context_course::instance($courseid);
$canmanage = has_capability('local/assessments:manage_public', $context) || has_capability('local/assessments:manage_secretive', context_system::instance());

global $DB;
$assessments = $DB->get_records('local_assessments', ['courseid' => $courseid], 'timecreated DESC');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('assessments', 'local_assessments'));

$table = new html_table();
$table->head = ['ID', 'Name', 'Type', 'Full mark', 'Weight', 'Average', 'Announcement date'];
$table->data = [];
foreach ($assessments as $a) {
    $table->data[] = [
        $a->publicid,
        $a->name,
        $a->type,
        $a->fullmark,
        $a->weight . '%',
        $a->averagescore !== null ? round($a->averagescore, 2) : '-',
        $a->announcementdate ? userdate($a->announcementdate) : '-',
    ];
}
if (!empty($table->data)) {
    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('no_assessments', 'local_assessments'), 'info');
}

if ($canmanage) {
    echo $OUTPUT->single_button(new moodle_url('/local/assessments/edit.php', ['courseid' => $courseid]), 'Add assessment', 'get');
}

echo $OUTPUT->footer();
