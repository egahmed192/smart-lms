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
     *
     * @param \core\event\message_sent $event
     */
    public static function message_sent(\core\event\message_sent $event): void {
        global $DB;
        $senderid = (int) $event->userid;
        $receiverid = (int) $event->relateduserid;
        $courseid = isset($event->other['courseid']) ? (int) $event->other['courseid'] : null;
        $messageid = (int) $event->objectid;

        $messageText = '';
        $msg = $DB->get_record('messages', ['id' => $messageid]);
        if ($msg) {
            $messageText = $msg->fullmessage ?? $msg->smallmessage ?? '';
        }

        $flagged = 0;
        $reason = '';
        $keywords = $DB->get_records('local_message_audit_keywords', null, 'id ASC');
        foreach ($keywords as $kw) {
            if (stripos($messageText, $kw->pattern) !== false) {
                $flagged = 1;
                $reason = $kw->pattern;
                break;
            }
        }

        $DB->insert_record('local_message_audit_log', (object)[
            'timecreated' => time(),
            'senderid' => $senderid,
            'receiverid' => $receiverid,
            'courseid' => $courseid,
            'contextid' => $event->contextid ?? null,
            'message_text' => $messageText,
            'metadata_json' => json_encode(['messageid' => $messageid]),
            'flagged' => $flagged,
            'reason' => $reason,
            'bulk_send' => 0,
        ]);
    }
}
