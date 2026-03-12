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
 * Get all student user IDs linked to a parent.
 *
 * @param int $parentuserid Moodle user id of the parent
 * @return int[] list of student user ids
 */
function local_parent_portal_get_students_for_parent(int $parentuserid): array {
    global $DB;
    $records = $DB->get_records('local_parent_portal_rel', ['parent_userid' => $parentuserid, 'active' => 1], '', 'student_userid');
    return array_map('intval', array_keys($records));
}

/**
 * Check whether user X is a parent of student Y.
 *
 * @param int $parentuserid Moodle user id of the parent
 * @param int $studentuserid Moodle user id of the student
 * @return bool
 */
function local_parent_portal_is_parent_of(int $parentuserid, int $studentuserid): bool {
    global $DB;
    return $DB->record_exists('local_parent_portal_rel', [
        'parent_userid' => $parentuserid,
        'student_userid' => $studentuserid,
        'active' => 1,
    ]);
}
