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
$assessmentid = required_param('assessmentid', PARAM_INT);
$course = get_course($courseid);
require_login($course);
$context = context_course::instance($courseid);

$canimport = has_capability('local/assessments:import_export', $context) || has_capability('local/assessments:manage_secretive', context_system::instance());
if (!$canimport) {
    throw new moodle_exception('nopermission');
}

global $DB;
$assessment = $DB->get_record('local_assessments', ['id' => $assessmentid, 'courseid' => $courseid]);
if (!$assessment) {
    throw new moodle_exception('invalidid');
}

$PAGE->set_url(new moodle_url('/local/assessments/import.php', ['id' => $courseid, 'assessmentid' => $assessmentid]));
$PAGE->set_title(get_string('import_evaluations', 'local_assessments'));
$PAGE->set_heading($course->fullname);

$mform = new \local_assessments\form\import_form(null, ['assessmentid' => $assessmentid, 'courseid' => $courseid]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/assessments/index.php', ['id' => $courseid]));
}
if ($data = $mform->get_data()) {
    $draftid = $data->csvfile;
    $fs = get_file_storage();
    $usercontext = context_user::instance($USER->id);
    $files = $fs->get_area_files($usercontext->id, 'user', 'draft', $draftid);
    $content = null;
    foreach ($files as $f) {
        if (!$f->is_directory()) {
            $content = $f->get_content();
            break;
        }
    }
    if (!$content) {
        \core\notification::error(get_string('filenotfound'));
    } else {
        $lines = array_map('str_getcsv', explode("\n", $content));
        $header = array_shift($lines);
        $studentcol = array_search('studentid', array_map('strtolower', $header));
        if ($studentcol === false) {
            $studentcol = array_search('email', array_map('strtolower', $header));
        }
        if ($studentcol === false) {
            $studentcol = array_search('secret_code', array_map('strtolower', $header));
        }
        $markcol = array_search('mark', array_map('strtolower', $header));
        $commentscol = array_search('comments', array_map('strtolower', $header));
        if ($studentcol === false || $markcol === false) {
            \core\notification::error('CSV must have studentid (or email or secret_code) and mark columns.');
        } else {
            $preview = [];
            $imported = 0;
            foreach ($lines as $idx => $row) {
                if (count($row) <= max($studentcol, $markcol)) {
                    continue;
                }
                $studentident = trim($row[$studentcol]);
                $mark = trim($row[$markcol]);
                $comments = ($commentscol !== false && isset($row[$commentscol])) ? trim($row[$commentscol]) : '';
                if ($studentident === '' || $mark === '') {
                    continue;
                }
                $studentid = null;
                if (is_numeric($studentident)) {
                    $studentid = (int) $studentident;
                } else {
                    $u = $DB->get_record('user', ['email' => $studentident, 'deleted' => 0]);
                    if ($u) {
                        $studentid = $u->id;
                    } else {
                        $sc = $DB->get_record('local_assessments_student', ['secret_code' => $studentident]);
                        if ($sc) {
                            $studentid = $sc->userid;
                        }
                    }
                }
                if (!$studentid) {
                    $preview[] = "Row " . ($idx + 2) . ": student not found for '$studentident'";
                    continue;
                }
                $enrolled = $DB->record_exists_sql("SELECT 1 FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid WHERE e.courseid = ? AND ue.userid = ?", [$courseid, $studentid]);
                if (!$enrolled) {
                    $preview[] = "Row " . ($idx + 2) . ": user $studentid not enrolled in course";
                    continue;
                }
                $markval = (float) $mark;
                $eval = $DB->get_record('local_assessments_eval', ['assessmentid' => $assessmentid, 'studentid' => $studentid]);
                if ($eval) {
                    $DB->update_record('local_assessments_eval', (object)['id' => $eval->id, 'mark' => $markval, 'comments' => $comments]);
                } else {
                    $DB->insert_record('local_assessments_eval', (object)[
                        'studentid' => $studentid,
                        'assessmentid' => $assessmentid,
                        'mark' => $markval,
                        'comments' => $comments,
                        'datecreated' => time(),
                    ]);
                }
                $imported++;
            }
            local_assessments_recalculate_assessment($assessmentid);
            if (!empty($preview)) {
                foreach ($preview as $p) {
                    \core\notification::warning($p);
                }
            }
            \core\notification::success(get_string('imported', 'local_assessments', $imported));
            redirect(new moodle_url('/local/assessments/evaluations.php', ['id' => $courseid, 'assessmentid' => $assessmentid]));
        }
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('import_evaluations', 'local_assessments'));
echo '<p>CSV columns: studentid (or email or secret_code), mark, comments (optional).</p>';
$mform->display();
echo $OUTPUT->footer();
