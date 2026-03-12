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

defined('MOODLE_INTERNAL') || die();

/**
 * Generate a unique 4-char alphanumeric secret code for a student.
 *
 * @param int $userid
 * @return string
 */
function local_assessments_generate_secret_code(int $userid): string {
    global $DB;
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    for ($i = 0; $i < 100; $i++) {
        $code = '';
        for ($j = 0; $j < 4; $j++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        if (!$DB->record_exists('local_assessments_student', ['secret_code' => $code])) {
            return $code;
        }
    }
    return substr(uniqid(), -4);
}

/**
 * Get or create secret code for a student.
 *
 * @param int $userid
 * @return string
 */
function local_assessments_get_secret_code(int $userid): string {
    global $DB;
    $rec = $DB->get_record('local_assessments_student', ['userid' => $userid]);
    if ($rec) {
        return $rec->secret_code;
    }
    $code = local_assessments_generate_secret_code($userid);
    $DB->insert_record('local_assessments_student', (object)[
        'userid' => $userid,
        'secret_code' => $code,
        'timemodified' => time(),
    ]);
    return $code;
}

/**
 * Get course total score for a student: sum of (mark/fullmark)*weight per assessment.
 *
 * @param int $studentid
 * @return array courseid => total score
 */
function local_assessments_get_course_totals(int $studentid): array {
    global $DB;
    $sql = "SELECT a.courseid, SUM((e.mark / NULLIF(a.fullmark, 0)) * a.weight) AS total
              FROM {local_assessments_eval} e
              JOIN {local_assessments} a ON a.id = e.assessmentid
             WHERE e.studentid = ?
          GROUP BY a.courseid";
    $rows = $DB->get_records_sql($sql, [$studentid]);
    $out = [];
    foreach ($rows as $r) {
        $out[$r->courseid] = (float) $r->total;
    }
    return $out;
}

/**
 * Whether the current user can see grades for this assessment (announcement date check).
 *
 * @param object $assessment
 * @return bool
 */
function local_assessments_can_see_grades(object $assessment): bool {
    if (has_capability('local/assessments:manage_secretive', context_system::instance())) {
        return true;
    }
    if (has_capability('local/assessments:manage_public', context_course::instance($assessment->courseid))) {
        return true;
    }
    if (empty($assessment->announcementdate)) {
        return true;
    }
    return $assessment->announcementdate <= time();
}

/**
 * Recalculate averagescore and ranks for an assessment after evaluations change.
 *
 * @param int $assessmentid
 */
function local_assessments_recalculate_assessment(int $assessmentid): void {
    global $DB;
    $evals = $DB->get_records('local_assessments_eval', ['assessmentid' => $assessmentid], 'mark DESC, id ASC');
    $sum = 0;
    $count = 0;
    $rank = 1;
    foreach ($evals as $e) {
        if ($e->mark !== null) {
            $sum += (float) $e->mark;
            $count++;
        }
        $DB->set_field('local_assessments_eval', 'rank', $rank, ['id' => $e->id]);
        $rank++;
    }
    $avg = $count > 0 ? $sum / $count : null;
    $DB->set_field('local_assessments', 'averagescore', $avg, ['id' => $assessmentid]);
}
