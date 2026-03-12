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
require_once($CFG->dirroot . '/local/odoo_sync/lib.php');
require_once($CFG->dirroot . '/enrol/lib.php');

require_login(null, false);
$studentid = required_param('studentid', PARAM_INT);
$PAGE->set_url(new moodle_url('/local/parent_portal/child.php', ['studentid' => $studentid]));
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_title(get_string('child_view', 'local_parent_portal'));
$PAGE->set_heading(get_string('child_view', 'local_parent_portal'));

require_capability('local/parent_portal:view_child_data', context_system::instance());

if (!local_parent_portal_is_parent_of($USER->id, $studentid)) {
    throw new moodle_exception('nopermission', 'local_parent_portal');
}
if (!local_odoo_sync_is_license_valid($studentid)) {
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('child_license_expired', 'local_parent_portal'), 'error');
    echo $OUTPUT->footer();
    exit;
}

global $DB;
$student = $DB->get_record('user', ['id' => $studentid, 'deleted' => 0]);
if (!$student) {
    throw new moodle_exception('invaliduserid');
}

$PAGE->set_heading(fullname($student));

echo $OUTPUT->header();
echo $OUTPUT->heading(fullname($student));

// Courses (enrolled).
$courses = enrol_get_all_users_courses($studentid, true);
if (!empty($courses)) {
    echo $OUTPUT->heading(get_string('child_courses', 'local_parent_portal'), 3);
    $list = [];
    foreach ($courses as $c) {
        $list[] = $c->fullname;
    }
    echo html_writer::tag('p', implode(', ', $list));
}

// Grades: if local_assessments exists, show course total; else core gradebook link.
if (function_exists('local_assessments_get_course_totals')) {
    $totals = local_assessments_get_course_totals($studentid);
    if (!empty($totals)) {
        echo $OUTPUT->heading(get_string('child_grades', 'local_parent_portal'), 3);
        $table = new html_table();
        $table->head = [get_string('course'), get_string('total_score', 'local_assessments')];
        $table->data = [];
        foreach ($totals as $courseid => $total) {
            $c = $DB->get_record('course', ['id' => $courseid]);
            $table->data[] = [$c ? $c->fullname : $courseid, round($total, 2)];
        }
        echo html_writer::table($table);
    }
} else {
    $gradeurl = new moodle_url('/grade/report/user/index.php', ['userid' => $studentid]);
    echo $OUTPUT->heading(get_string('child_grades', 'local_parent_portal'), 3);
    echo html_writer::link($gradeurl, get_string('view_grades', 'local_parent_portal'));
}

echo $OUTPUT->footer();
