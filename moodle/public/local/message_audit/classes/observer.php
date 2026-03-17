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

namespace local_message_audit;

defined('MOODLE_INTERNAL') || die();

class observer {

    /**
     * Log message and run keyword rules when a message is sent.
     * Enforces student–parent restriction: only users with message_student_parent
     * may send messages between student and parent; otherwise the message is removed.
     *
     * @param \core\event\message_sent $event
     */
    public static function message_sent(\core\event\message_sent $event): void {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/local/message_audit/lib.php');
        $senderid = (int) $event->userid;
        $receiverid = (int) $event->relateduserid;
        $courseid = isset($event->other['courseid']) ? (int) $event->other['courseid'] : null;
        // Messaging API uses the site course (id=1) for direct messages; treat that as "no course" in our log.
        if ($courseid === 1) {
            $courseid = null;
        }
        $messageid = (int) $event->objectid;

        $messageText = '';
        $msg = $DB->get_record('messages', ['id' => $messageid]);
        if ($msg) {
            $messageText = $msg->fullmessage ?? $msg->smallmessage ?? '';
        }

        $flagged = 0;
        $reason = '';
        $matchedaction = '';

        // Student–parent restriction: only teachers/supervisors (with capability) may send student–parent messages.
        $studentparentviolation = false;
        if (\local_message_audit_is_student_parent_exchange($senderid, $receiverid)) {
            $context = \context_system::instance();
            $sender = $DB->get_record('user', ['id' => $senderid]);
            if ($sender && !has_capability('local/message_audit:message_student_parent', $context, $sender)) {
                $studentparentviolation = true;
                $reason = get_string('student_parent_violation', 'local_message_audit');
            }
        }

        if (!$studentparentviolation) {
            $keywords = $DB->get_records('local_message_audit_keywords', null, 'id ASC');
            foreach ($keywords as $kw) {
                if (stripos($messageText, $kw->pattern) !== false) {
                    $flagged = 1;
                    $reason = $kw->pattern;
                    $matchedaction = $kw->action ?? 'flag';
                    break;
                }
            }
            // Optional: flag Egyptian phone numbers.
            if (!$flagged && (int)get_config('local_message_audit', 'flag_egyptian_phones')) {
                // Match Egyptian numbers like: 01xxxxxxxxx, +201xxxxxxxxx, 00201xxxxxxxxx (allow spaces/dashes).
                $re = '/(?:\\+?20|0020)?\\s*0?1\\s*[0-9\\s-]{9,12}/u';
                if (preg_match($re, $messageText)) {
                    $flagged = 1;
                    $reason = get_string('flag_reason_egyptian_phone', 'local_message_audit');
                    $matchedaction = 'flag';
                }
            }
        } else {
            $flagged = 1;
        }

        $DB->insert_record('local_message_audit_log', (object)[
            'timecreated' => time(),
            'senderid' => $senderid,
            'receiverid' => $receiverid,
            'courseid' => $courseid,
            'contextid' => $event->contextid ?? null,
            'message_text' => $messageText,
            'metadata_json' => json_encode(['messageid' => $messageid, 'student_parent_violation' => $studentparentviolation]),
            'flagged' => $flagged,
            'reason' => $reason,
            'bulk_send' => 0,
        ]);

        if ($studentparentviolation) {
            $DB->delete_records('messages', ['id' => $messageid]);
        }

        if ($flagged && ($matchedaction === 'notify_admin' || $matchedaction === 'flag_and_notify')) {
            self::notify_view_logs_users($senderid, $receiverid, $reason, $messageText);
        }
    }

    /**
     * Send a notification to users with view_logs capability when a message is flagged with notify_admin.
     */
    private static function notify_view_logs_users(int $senderid, int $receiverid, string $reason, string $messagepreview): void {
        global $DB;
        $context = \context_system::instance();
        $recipients = get_users_by_capability($context, 'local/message_audit:view_logs', 'u.id, u.lang');
        if (empty($recipients)) {
            return;
        }
        $sender = $DB->get_record('user', ['id' => $senderid], 'id, firstname, lastname');
        $receiver = $DB->get_record('user', ['id' => $receiverid], 'id, firstname, lastname');
        $from = \core_user::get_user(\core_user::NOREPLY_USER);
        $subject = get_string('message_flagged_notify_subject', 'local_message_audit');
        $body = get_string('message_flagged_notify_body', 'local_message_audit', (object)[
            'sender' => $sender ? fullname($sender) : $senderid,
            'receiver' => $receiver ? fullname($receiver) : $receiverid,
            'reason' => $reason,
            'preview' => shorten_text($messagepreview, 200),
        ]);
        foreach ($recipients as $u) {
            if ((int)$u->id === $senderid || (int)$u->id === $receiverid) {
                continue;
            }
            $userto = \core_user::get_user($u->id);
            $message = new \core\message\message();
            $message->component = 'local_message_audit';
            $message->name = 'flagged_notify';
            $message->userfrom = $from;
            $message->userto = $userto;
            $message->subject = $subject;
            $message->fullmessage = $body;
            $message->fullmessageformat = FORMAT_PLAIN;
            $message->fullmessagehtml = '';
            $message->smallmessage = $body;
            $message->notification = 1;
            $message->contexturl = (new \moodle_url('/local/message_audit/index.php'))->out(false);
            $message->contexturlname = get_string('message_log', 'local_message_audit');
            message_send($message);
        }
    }
}
