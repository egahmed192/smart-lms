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
require_once($CFG->dirroot . '/enrol/lib.php');

$courseid = required_param('id', PARAM_INT);
$assessmentid = required_param('assessmentid', PARAM_INT);
$course = get_course($courseid);
require_login($course);
$context = context_course::instance($courseid);

$canpublic = has_capability('local/assessments:manage_public', $context);
$cansecretive = has_capability('local/assessments:manage_secretive', context_system::instance());

global $DB;
$assessment = $DB->get_record('local_assessments', ['id' => $assessmentid, 'courseid' => $courseid]);
if (!$assessment) {
    throw new moodle_exception('invalidid');
}
if ($assessment->type === 'secretive' && !$cansecretive) {
    throw new moodle_exception('nopermission');
}
if ($assessment->type === 'public' && !$canpublic && !$cansecretive) {
    throw new moodle_exception('nopermission');
}

$PAGE->set_url(new moodle_url('/local/assessments/evaluations.php', ['id' => $courseid, 'assessmentid' => $assessmentid]));
$PAGE->set_title(get_string('evaluations', 'local_assessments') . ': ' . $assessment->name);
$PAGE->set_heading($course->fullname);

$students = get_enrolled_users($context, 'mod/assign:submit', 0, 'u.id, u.firstname, u.lastname', 'u.lastname, u.firstname');

if (optional_param('save', 0, PARAM_INT) && confirm_sesskey()) {
    $fullmark = $assessment->fullmark;
    foreach ($students as $st) {
        $mark = optional_param('mark_' . $st->id, null, PARAM_FLOAT);
        $comments = optional_param('comments_' . $st->id, '', PARAM_RAW);
        $eval = $DB->get_record('local_assessments_eval', ['assessmentid' => $assessmentid, 'studentid' => $st->id]);
        if ($mark !== null && $mark !== '') {
            if ($eval) {
                $DB->update_record('local_assessments_eval', (object)[
                    'id' => $eval->id,
                    'mark' => $mark,
                    'comments' => $comments,
                ]);
            } else {
                $DB->insert_record('local_assessments_eval', (object)[
                    'studentid' => $st->id,
                    'assessmentid' => $assessmentid,
                    'mark' => $mark,
                    'comments' => $comments,
                    'datecreated' => time(),
                ]);
            }
        }
    }
    local_assessments_recalculate_assessment($assessmentid);
    redirect(new moodle_url('/local/assessments/evaluations.php', ['id' => $courseid, 'assessmentid' => $assessmentid]), get_string('changessaved'));
}

$evals = $DB->get_records('local_assessments_eval', ['assessmentid' => $assessmentid]);
$evalbystudent = [];
foreach ($evals as $e) {
    $evalbystudent[$e->studentid] = $e;
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('evaluations', 'local_assessments') . ': ' . $assessment->name);

$form = '<form method="post" action="evaluations.php"><input type="hidden" name="id" value="' . (int)$courseid . '"/>';
$form .= '<input type="hidden" name="assessmentid" value="' . (int)$assessmentid . '"/>';
$form .= '<input type="hidden" name="save" value="1"/><input type="hidden" name="sesskey" value="' . sesskey() . '"/>';
$form .= '<table class="generaltable"><thead><tr><th>' . get_string('student') . '</th><th>' . get_string('mark', 'local_assessments') . '</th><th>' . get_string('comments', 'local_assessments') . '</th><th>' . get_string('rank', 'local_assessments') . '</th></tr></thead><tbody>';

foreach ($students as $st) {
    $e = $evalbystudent[$st->id] ?? null;
    $markval = $e ? $e->mark : '';
    $commentsval = $e ? $e->comments : '';
    $rankval = $e && $e->rank !== null ? $e->rank : '-';
    $form .= '<tr><td>' . fullname($st) . '</td>';
    $form .= '<td><input type="number" step="0.01" name="mark_' . $st->id . '" value="' . s($markval) . '" size="6"/></td>';
    $form .= '<td><input type="text" name="comments_' . $st->id . '" value="' . s($commentsval) . '" size="40"/></td>';
    $form .= '<td>' . $rankval . '</td></tr>';
}
$form .= '</tbody></table><button type="submit" class="btn btn-primary">' . get_string('savechanges') . '</button></form>';

echo $form;

echo html_writer::link(new moodle_url('/local/assessments/index.php', ['id' => $courseid]), get_string('back'));
echo $OUTPUT->footer();
