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

$courseid = required_param('courseid', PARAM_INT);
$assessmentid = optional_param('id', 0, PARAM_INT);
$course = get_course($courseid);
require_login($course);
$context = context_course::instance($courseid);

$canpublic = has_capability('local/assessments:manage_public', $context);
$cansecretive = has_capability('local/assessments:manage_secretive', context_system::instance());
if (!$canpublic && !$cansecretive) {
    throw new moodle_exception('nopermission');
}

$PAGE->set_url(new moodle_url('/local/assessments/edit.php', ['courseid' => $courseid, 'id' => $assessmentid]));
$PAGE->set_title($assessmentid ? get_string('edit_assessment', 'local_assessments') : get_string('add_assessment', 'local_assessments'));
$PAGE->set_heading($course->fullname);

global $DB;
$assessment = $assessmentid ? $DB->get_record('local_assessments', ['id' => $assessmentid, 'courseid' => $courseid]) : null;
if ($assessmentid && !$assessment) {
    throw new moodle_exception('invalidid');
}

$customdata = ['courseid' => $courseid, 'assessment' => $assessment, 'can_secretive' => $cansecretive];
$mform = new \local_assessments\form\assessment_form(null, $customdata);

if ($assessment) {
    $data = (object)[
        'id' => $assessment->id,
        'courseid' => $assessment->courseid,
        'name' => $assessment->name,
        'publicid' => $assessment->publicid,
        'type' => $assessment->type,
        'fullmark' => $assessment->fullmark,
        'weight' => $assessment->weight,
        'announcementdate' => $assessment->announcementdate,
        'assessmentdate' => $assessment->assessmentdate,
    ];
    $data->description = ['text' => $assessment->description, 'format' => FORMAT_HTML];
    $mform->set_data($data);
}

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/assessments/index.php', ['id' => $courseid]));
}
if ($data = $mform->get_data()) {
    $now = time();
    $record = (object)[
        'name' => $data->name,
        'publicid' => $data->publicid ?: ('ASS-' . $courseid . '-' . $now),
        'type' => $cansecretive ? $data->type : 'public',
        'fullmark' => $data->fullmark,
        'weight' => $data->weight,
        'description' => '',
        'announcementdate' => $data->announcementdate ?? 0,
        'assessmentdate' => $data->assessmentdate ?? 0,
        'courseid' => $courseid,
        'timemodified' => $now,
    ];
    if (isset($data->description['text'])) {
        $record->description = $data->description['text'];
    }
    if ($assessment) {
        $record->id = $assessment->id;
        $record->createdby = $assessment->createdby;
        $record->timecreated = $assessment->timecreated;
        $DB->update_record('local_assessments', $record);
        redirect(new moodle_url('/local/assessments/index.php', ['id' => $courseid]), get_string('changessaved'));
    } else {
        $record->createdby = $USER->id;
        $record->timecreated = $now;
        $record->id = $DB->insert_record('local_assessments', $record);
        if (empty($data->publicid)) {
            $DB->set_field('local_assessments', 'publicid', 'ASS-' . $courseid . '-' . $record->id, ['id' => $record->id]);
        }
        redirect(new moodle_url('/local/assessments/index.php', ['id' => $courseid]), get_string('changessaved'));
    }
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
