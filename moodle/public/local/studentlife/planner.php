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
require_once($CFG->dirroot . '/course/lib.php');

require_login();

$courseid = optional_param('courseid', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT); // Task id for edit.
$action = optional_param('action', '', PARAM_ALPHA); // complete|incomplete|delete

$baseurl = new moodle_url('/local/studentlife/planner.php');
if ($courseid) {
    $baseurl->param('courseid', $courseid);
}

$PAGE->set_url($baseurl);
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_title(get_string('planner', 'local_studentlife'));
$PAGE->set_heading(get_string('planner:heading', 'local_studentlife'));

// Build course options for "Add/edit task" form.
$enrolled = course_get_enrolled_courses_for_logged_in_user(0, 0, null, 'id,fullname,shortname');
$courseoptions = [0 => get_string('none')];
foreach ($enrolled as $c) {
    $courseoptions[$c->id] = format_string($c->fullname);
}

// Handle quick actions.
if ($action && $id) {
    require_sesskey();
    if ($action === 'complete') {
        \local_studentlife\local\planner_repository::set_completed($id, $USER->id, true);
        redirect($baseurl, get_string('changessaved'));
    }
    if ($action === 'incomplete') {
        \local_studentlife\local\planner_repository::set_completed($id, $USER->id, false);
        redirect($baseurl, get_string('changessaved'));
    }
    if ($action === 'delete') {
        \local_studentlife\local\planner_repository::delete_task($id, $USER->id);
        redirect($baseurl, get_string('changessaved'));
    }
}

$task = null;
if ($id) {
    $task = \local_studentlife\local\planner_repository::get_task($id, $USER->id);
}

$customdata = [
    'task' => $task,
    'courseid' => $courseid ?: null,
    'courseoptions' => $courseoptions,
];
$mform = new \local_studentlife\form\task_form(null, $customdata);

if ($task) {
    $mform->set_data((object)[
        'id' => $task->id,
        'courseid' => $task->courseid ?: 0,
        'name' => $task->name,
        'description' => $task->description,
        'estimatedminutes' => $task->estimatedminutes,
        'duetime' => $task->duetime,
    ]);
}

if ($mform->is_cancelled()) {
    redirect($baseurl);
}

if ($data = $mform->get_data()) {
    $now = time();
    $record = (object)[
        'userid' => $USER->id,
        'courseid' => !empty($data->courseid) ? (int)$data->courseid : null,
        'name' => $data->name,
        'description' => $data->description ?? null,
        'estimatedminutes' => ($data->estimatedminutes !== '' && $data->estimatedminutes !== null) ? (int)$data->estimatedminutes : null,
        'duetime' => $data->duetime ?: null,
        'cmid' => null,
        'timemodified' => $now,
    ];

    if (!empty($data->id)) {
        $existing = \local_studentlife\local\planner_repository::get_task((int)$data->id, $USER->id);
        $record->id = (int)$data->id;
        $record->timecreated = $existing->timecreated;
        $record->completed = $existing->completed;
        $record->timecompleted = $existing->timecompleted;
    } else {
        $record->timecreated = $now;
        $record->completed = 0;
        $record->timecompleted = null;
    }

    \local_studentlife\local\planner_repository::upsert_task($record);
    redirect($baseurl, get_string('changessaved'));
}

$tasks = \local_studentlife\local\planner_repository::get_tasks_for_user($USER->id, $courseid ?: null);
$progress = \local_studentlife\local\planner_repository::get_progress_by_course($USER->id);

$taskrows = [];
foreach ($tasks as $t) {
    $coursename = '';
    if (!empty($t->courseid) && isset($enrolled[$t->courseid])) {
        $coursename = format_string($enrolled[$t->courseid]->fullname);
    }
    $taskrows[] = [
        'id' => (int)$t->id,
        'name' => format_string($t->name),
        'description' => format_string($t->description ?? ''),
        'course' => $coursename,
        'estimatedminutes' => $t->estimatedminutes ? (int)$t->estimatedminutes : null,
        'duetime' => $t->duetime ? userdate($t->duetime) : null,
        'completed' => !empty($t->completed),
        'editurl' => (new moodle_url('/local/studentlife/planner.php', ['id' => $t->id] + ($courseid ? ['courseid' => $courseid] : [])))->out(false),
        'completeurl' => (new moodle_url('/local/studentlife/planner.php', ['id' => $t->id, 'action' => 'complete', 'sesskey' => sesskey()] + ($courseid ? ['courseid' => $courseid] : [])))->out(false),
        'incompleteurl' => (new moodle_url('/local/studentlife/planner.php', ['id' => $t->id, 'action' => 'incomplete', 'sesskey' => sesskey()] + ($courseid ? ['courseid' => $courseid] : [])))->out(false),
        'deleteurl' => (new moodle_url('/local/studentlife/planner.php', ['id' => $t->id, 'action' => 'delete', 'sesskey' => sesskey()] + ($courseid ? ['courseid' => $courseid] : [])))->out(false),
    ];
}

// Course progress cards.
$progresscards = [];
foreach ($progress as $cid => $p) {
    if (!isset($enrolled[$cid])) {
        continue;
    }
    $total = max(0, (int)$p['total']);
    $completed = max(0, (int)$p['completed']);
    $percent = $total > 0 ? (int)round(($completed / $total) * 100) : 0;
    $progresscards[] = [
        'courseid' => $cid,
        'coursename' => format_string($enrolled[$cid]->fullname),
        'completed' => $completed,
        'total' => $total,
        'percent' => $percent,
        'url' => (new moodle_url('/local/studentlife/planner.php', ['courseid' => $cid]))->out(false),
    ];
}

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_studentlife/planner', [
    'baseurl' => $baseurl->out(false),
    'hasprogress' => !empty($progresscards),
    'progresscards' => $progresscards,
    'has_tasks' => !empty($taskrows),
    'tasks' => $taskrows,
    'filtering_course' => $courseid ? format_string($enrolled[$courseid]->fullname ?? '') : null,
]);

echo html_writer::tag('hr', '');
echo html_writer::tag('h3', $id ? get_string('planner:edittask', 'local_studentlife') : get_string('planner:addtask', 'local_studentlife'),
    ['class' => 'h5']);
$mform->display();

echo $OUTPUT->footer();

