<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * Check whether a user is registered as a parent (has at least one student in parent_portal_rel).
 *
 * @param int $userid Moodle user id
 * @return bool
 */
function local_message_audit_is_parent(int $userid): bool {
    global $DB;
    return $DB->record_exists('local_parent_portal_rel', ['parent_userid' => $userid, 'active' => 1]);
}

/**
 * Check whether a user has the student role in any context.
 *
 * @param int $userid Moodle user id
 * @return bool
 */
function local_message_audit_is_student(int $userid): bool {
    global $DB;
    $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
    if (!$studentrole) {
        return false;
    }
    return $DB->record_exists('role_assignments', ['userid' => $userid, 'roleid' => $studentrole]);
}

/**
 * Check whether this is a student–parent (or parent–student) conversation.
 * Only one of the two users must be student and the other parent.
 *
 * @param int $senderid sender user id
 * @param int $receiverid receiver user id
 * @return bool
 */
function local_message_audit_is_student_parent_exchange(int $senderid, int $receiverid): bool {
    $senderstudent = local_message_audit_is_student($senderid);
    $senderparent = local_message_audit_is_parent($senderid);
    $receiverstudent = local_message_audit_is_student($receiverid);
    $receiverparent = local_message_audit_is_parent($receiverid);
    return ($senderstudent && $receiverparent) || ($senderparent && $receiverstudent);
}
