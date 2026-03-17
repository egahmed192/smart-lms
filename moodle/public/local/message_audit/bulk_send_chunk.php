<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.moodle.org/licenses/>.

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Ensure no output before JSON (e.g. from config or session).
if (ob_get_level()) {
    ob_end_clean();
}
header('Content-Type: application/json; charset=utf-8');

try {
    require_sesskey();
    require_capability('local/message_audit:send_bulk_message', context_system::instance());
} catch (Exception $e) {
    echo json_encode(['done' => true, 'sent' => 0, 'total' => 0, 'error' => 'auth']);
    exit;
}

$chunksize = 15;
$sent = 0;
$done = false;
$error = null;

if (!isset($_SESSION['local_message_audit_bulk_recipients']) || !is_array($_SESSION['local_message_audit_bulk_recipients'])) {
    echo json_encode(['done' => true, 'sent' => 0, 'total' => 0, 'error' => 'nodata']);
    exit;
}

$recipients = $_SESSION['local_message_audit_bulk_recipients'];
$message = $_SESSION['local_message_audit_bulk_message'] ?? '';
$courseid = (int)($_SESSION['local_message_audit_bulk_courseid'] ?? 0);
$target = $_SESSION['local_message_audit_bulk_target'] ?? '';
$classkey = $_SESSION['local_message_audit_bulk_classkey'] ?? '';
$cohortid = (int)($_SESSION['local_message_audit_bulk_cohortid'] ?? 0);
$offset = (int)($_SESSION['local_message_audit_bulk_offset'] ?? 0);
$total_sent_so_far = (int)($_SESSION['local_message_audit_bulk_total_sent'] ?? 0);
$total = count($recipients);

if ($message === '' || $total === 0) {
    unset($_SESSION['local_message_audit_bulk_recipients'], $_SESSION['local_message_audit_bulk_message'],
        $_SESSION['local_message_audit_bulk_offset'], $_SESSION['local_message_audit_bulk_total_sent'],
        $_SESSION['local_message_audit_bulk_courseid'], $_SESSION['local_message_audit_bulk_target'],
        $_SESSION['local_message_audit_bulk_classkey'], $_SESSION['local_message_audit_bulk_cohortid']);
    echo json_encode(['done' => true, 'sent' => 0, 'total' => $total, 'error' => 'invalid']);
    exit;
}

require_once($CFG->dirroot . '/message/lib.php');
global $DB, $USER;

$batch = array_slice($recipients, $offset, $chunksize);
$failed = 0;
$lasterror = null;
foreach ($batch as $recid) {
    $recid = (int) $recid;
    if ($recid == $USER->id) {
        continue;
    }
    try {
        // get_conversation_between_users() returns the conversation id (int), not an object.
        $convid = \core_message\api::get_conversation_between_users([$USER->id, $recid]);
        if (!$convid) {
            $conv = \core_message\api::create_conversation(
                \core_message\api::MESSAGE_CONVERSATION_TYPE_INDIVIDUAL,
                [$USER->id, $recid]
            );
            $convid = (int)$conv->id;
        }
        $sentmsg = \core_message\api::send_message_to_conversation($USER->id, $convid, $message, FORMAT_PLAIN);

        // The message_sent observer already logs this message with bulk_send=0.
        // Upgrade that existing log row to bulk_send=1 to avoid duplicates.
        $msgid = isset($sentmsg->id) ? (int)$sentmsg->id : 0;
        $updated = false;
        if ($msgid > 0) {
            $like = '%"messageid":' . $msgid . '%';
            $select = "senderid = :sid AND receiverid = :rid AND " . $DB->sql_like('metadata_json', ':mj', false, false);
            $selparams = ['sid' => $USER->id, 'rid' => $recid, 'mj' => $like];
            $updated = $DB->set_field_select('local_message_audit_log', 'bulk_send', 1, $select, $selparams);
            if ($courseid) {
                $DB->set_field_select('local_message_audit_log', 'courseid', $courseid, $select, $selparams);
            }
        }
        if (!$updated) {
            // Fallback: if no observer row exists for any reason, insert a bulk log row.
            $DB->insert_record('local_message_audit_log', (object)[
                'timecreated' => time(),
                'senderid' => $USER->id,
                'receiverid' => $recid,
                'courseid' => $courseid ?: null,
                'contextid' => null,
                'message_text' => $message,
                'metadata_json' => json_encode(['bulk' => true, 'target' => $target, 'classkey' => $classkey, 'cohortid' => $cohortid]),
                'flagged' => 0,
                'reason' => null,
                'bulk_send' => 1,
            ]);
        }
        $sent++;
    } catch (\Throwable $e) {
        $failed++;
        if ($lasterror === null) {
            $lasterror = $e->getMessage();
        }
    }
}

$new_offset = $offset + count($batch);
$new_total_sent = $total_sent_so_far + $sent;
$_SESSION['local_message_audit_bulk_offset'] = $new_offset;
$_SESSION['local_message_audit_bulk_total_sent'] = $new_total_sent;

if ($new_offset >= $total) {
    $done = true;
    if ($new_total_sent > 0) {
        $msg = new \core\message\message();
        $msg->component = 'local_message_audit';
        $msg->name = 'bulk_send_confirmation';
        $msg->userfrom = \core_user::get_user(\core_user::NOREPLY_USER);
        $msg->userto = $USER;
        $msg->subject = get_string('bulk_send_notification', 'local_message_audit', $new_total_sent);
        $msg->fullmessage = get_string('bulk_send_notification', 'local_message_audit', $new_total_sent);
        $msg->fullmessageformat = FORMAT_PLAIN;
        $msg->smallmessage = get_string('bulk_send_notification', 'local_message_audit', $new_total_sent);
        $msg->notification = 1;
        message_send($msg);
    }
    unset($_SESSION['local_message_audit_bulk_recipients'], $_SESSION['local_message_audit_bulk_message'],
        $_SESSION['local_message_audit_bulk_offset'], $_SESSION['local_message_audit_bulk_total_sent'],
        $_SESSION['local_message_audit_bulk_courseid'], $_SESSION['local_message_audit_bulk_target'],
        $_SESSION['local_message_audit_bulk_classkey'], $_SESSION['local_message_audit_bulk_cohortid']);
}

echo json_encode([
    'done' => $done,
    'sent' => $new_total_sent,
    'total' => $total,
    'failed' => $failed,
    'error' => $error,
    'lasterror' => $lasterror,
]);
