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

namespace local_studentlife\task;

defined('MOODLE_INTERNAL') || die();

final class build_offline_pack extends \core\task\adhoc_task {
    public function execute(): void {
        $data = (array)$this->get_custom_data();
        $userid = (int)($data['userid'] ?? 0);
        $courseid = (int)($data['courseid'] ?? 0);
        $includeindex = !empty($data['includeindex']);

        if ($userid <= 0 || $courseid <= 0) {
            return;
        }

        // Build the pack (stored in user context).
        $file = \local_studentlife\local\offline_pack_service::build_and_store_pack($userid, $courseid, $includeindex);

        // Notify the user.
        $user = \core_user::get_user($userid, '*', MUST_EXIST);
        $noreply = \core_user::get_noreply_user();
        $url = (new \moodle_url('/local/studentlife/offlinepack.php', ['courseid' => $courseid]))->out(false);

        $message = new \core\message\message();
        $message->component = 'local_studentlife';
        $message->name = 'offlinepackready';
        $message->userfrom = $noreply;
        $message->userto = $user;
        $message->subject = get_string('offlinepack:ready', 'local_studentlife');
        $message->fullmessage = get_string('offlinepack:ready', 'local_studentlife') . "\n" . $url;
        $message->fullmessageformat = FORMAT_PLAIN;
        $message->fullmessagehtml = '<p>' . s(get_string('offlinepack:ready', 'local_studentlife')) . '</p>' .
            '<p><a href="' . s($url) . '">Download</a></p>';
        $message->smallmessage = get_string('offlinepack:ready', 'local_studentlife');
        $message->notification = 1;

        message_send($message);
    }
}

