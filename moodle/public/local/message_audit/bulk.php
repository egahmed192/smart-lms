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
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_message_audit_bulk');
require_capability('local/message_audit:send_bulk_message', context_system::instance());

$PAGE->set_url(new moodle_url('/local/message_audit/bulk.php'));
$PAGE->set_title(get_string('bulk_message', 'local_message_audit'));
$PAGE->set_heading(get_string('bulk_message', 'local_message_audit'));

global $DB;

$target = optional_param('target', 'students', PARAM_ALPHA);
$courseid = optional_param('courseid', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);
$message = optional_param('message', '', PARAM_RAW);

$recipients = [];
if ($target === 'students') {
    $recipients = $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname = 'student'");
} elseif ($target === 'teachers') {
    $recipients = $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname IN ('teacher', 'editingteacher')");
} elseif ($target === 'parents') {
    $recipients = $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname = 'parent'");
} elseif ($target === 'all') {
    $recipients = $DB->get_fieldset_sql("SELECT id FROM {user} WHERE deleted = 0 AND id > 1");
}
if ($courseid > 0) {
    $enrolled = $DB->get_fieldset_sql("SELECT userid FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid WHERE e.courseid = ?", [$courseid]);
    $recipients = array_intersect($recipients, $enrolled);
}
$recipients = array_values(array_unique(array_filter($recipients)));

if ($confirm && $message !== '' && confirm_sesskey()) {
    require_once($CFG->dirroot . '/message/lib.php');
    $count = 0;
    foreach ($recipients as $recid) {
        if ($recid == $USER->id) {
            continue;
        }
        try {
            $conv = \core_message\api::get_conversation_between_users([$USER->id, $recid]);
            if (!$conv) {
                $conv = \core_message\api::create_conversation(
                    \core_message\api::MESSAGE_CONVERSATION_TYPE_INDIVIDUAL,
                    [$USER->id, $recid]
                );
            }
            \core_message\api::send_message_to_conversation($USER->id, $conv->id, $message, FORMAT_PLAIN);
            $DB->insert_record('local_message_audit_log', (object)[
                'timecreated' => time(),
                'senderid' => $USER->id,
                'receiverid' => $recid,
                'courseid' => $courseid ?: null,
                'contextid' => null,
                'message_text' => $message,
                'metadata_json' => json_encode(['bulk' => true, 'target' => $target]),
                'flagged' => 0,
                'reason' => null,
                'bulk_send' => 1,
            ]);
            $count++;
        } catch (\Throwable $e) {
            // Skip failed recipient.
        }
    }
    redirect(new moodle_url('/local/message_audit/bulk.php'), get_string('bulk_sent', 'local_message_audit', $count), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('bulk_message', 'local_message_audit'));

$form = '<form method="get" action="bulk.php">';
$form .= '<label>Target: </label><select name="target"><option value="students"' . ($target === 'students' ? ' selected' : '') . '>Students</option>';
$form .= '<option value="teachers"' . ($target === 'teachers' ? ' selected' : '') . '>Teachers</option>';
$form .= '<option value="parents"' . ($target === 'parents' ? ' selected' : '') . '>Parents</option>';
$form .= '<option value="all"' . ($target === 'all' ? ' selected' : '') . '>All users</option></select> ';
$form .= '<label>Course (optional): </label><input type="number" name="courseid" value="' . s($courseid) . '" min="0"> ';
$form .= '<button type="submit">Update count</button></form>';
echo $form;

echo '<p>Recipients: ' . count($recipients) . '</p>';

$form2 = '<form method="post" action="bulk.php">';
$form2 .= '<input type="hidden" name="target" value="' . s($target) . '">';
$form2 .= '<input type="hidden" name="courseid" value="' . (int)$courseid . '">';
$form2 .= '<input type="hidden" name="confirm" value="1">';
$form2 .= '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
$form2 .= '<label>Message:</label><br><textarea name="message" rows="4" cols="60" required></textarea><br>';
$form2 .= '<button type="submit">Send to ' . count($recipients) . ' recipients</button></form>';
echo $form2;

echo $OUTPUT->footer();
