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

// Load class/grade information from Odoo sync mapping.
$classname = '';
$map = $DB->get_record('local_odoo_sync_map', ['userid' => $studentid, 'odoo_type' => 'student']);
if ($map) {
    $yearname = '';
    $standardname = '';
    if (!empty($map->year_apply_for_id)) {
        $yearrec = $DB->get_record('local_odoo_sync_year', ['odoo_id' => $map->year_apply_for_id]);
        if ($yearrec) {
            $yearname = $yearrec->display_name;
        }
    }
    if (!empty($map->standard_id)) {
        $standardrec = $DB->get_record('local_odoo_sync_standard', ['odoo_id' => $map->standard_id]);
        if ($standardrec) {
            $standardname = $standardrec->display_name;
        }
    }
    $classname = trim($yearname . ' ' . $standardname);
}

// Determine license status text (here we only reach this point if license is valid).
$licensetext = get_string('license_status_active', 'local_parent_portal');

$PAGE->set_heading(fullname($student));

echo $OUTPUT->header();

// Child header with class and license info.
echo html_writer::start_div('mb-4');
echo html_writer::tag('h2', fullname($student));
if ($classname !== '') {
    echo html_writer::tag('p',
        get_string('child_class_label', 'local_parent_portal', $classname),
        ['class' => 'text-muted']
    );
}
echo html_writer::tag('span', $licensetext, ['class' => 'badge bg-success']);
echo html_writer::end_div();

// Courses (enrolled).
$courses = enrol_get_all_users_courses($studentid, true);
echo $OUTPUT->heading(get_string('child_courses', 'local_parent_portal'), 3);
if (!empty($courses)) {
    $table = new html_table();
    $table->head = [get_string('course'), get_string('category')];
    $table->data = [];
    foreach ($courses as $c) {
        $catname = '';
        if (!empty($c->category)) {
            $cat = $DB->get_record('course_categories', ['id' => $c->category], 'name');
            $catname = $cat ? $cat->name : '';
        }
        $table->data[] = [$c->fullname, $catname];
    }
    echo html_writer::table($table);
} else {
    echo html_writer::tag('p', get_string('child_no_courses', 'local_parent_portal'));
}

// Grades: if local_assessments exists, show course total; else core gradebook link.
echo $OUTPUT->heading(get_string('child_grades', 'local_parent_portal'), 3);
if (function_exists('local_assessments_get_course_totals')) {
    $totals = local_assessments_get_course_totals($studentid);
    if (!empty($totals)) {
        $table = new html_table();
        $table->head = [get_string('course'), get_string('total_score', 'local_assessments')];
        $table->data = [];
        foreach ($totals as $courseid => $total) {
            $c = $DB->get_record('course', ['id' => $courseid]);
            $table->data[] = [$c ? $c->fullname : $courseid, round($total, 2)];
        }
        echo html_writer::table($table);
    } else {
        echo html_writer::tag('p', get_string('child_no_grades', 'local_parent_portal'));
    }
} else {
    $gradeurl = new moodle_url('/grade/report/user/index.php', ['userid' => $studentid]);
    echo html_writer::tag('p', get_string('child_grades_link_intro', 'local_parent_portal'));
    echo html_writer::link($gradeurl, get_string('view_grades', 'local_parent_portal'));
}

echo $OUTPUT->footer();
