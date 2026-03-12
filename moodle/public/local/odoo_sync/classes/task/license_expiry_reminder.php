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

namespace local_odoo_sync\task;

defined('MOODLE_INTERNAL') || die();

class license_expiry_reminder extends \core\task\scheduled_task {

    public function get_name(): string {
        return get_string('task_license_expiry_reminder', 'local_odoo_sync');
    }

    public function execute(): void {
        global $DB;
        $days = (int) get_config('local_odoo_sync', 'license_expiry_days_before');
        if ($days <= 0) {
            $days = 7;
        }
        $from = time();
        $to = $from + ($days * DAYSECS);
        $records = $DB->get_records_sql(
            "SELECT l.userid, l.license_expiry FROM {local_odoo_sync_lic} l
             WHERE l.license_status = 'active' AND l.license_expiry > 0
               AND l.license_expiry >= ? AND l.license_expiry <= ?",
            [$from, $to]
        );
        $fromuser = \core_user::get_user(\core_user::NOREPLY_USER);
        foreach ($records as $r) {
            $userto = \core_user::get_user($r->userid);
            if (!$userto || $userto->deleted) {
                continue;
            }
            $expirydate = userdate($r->license_expiry, get_string('strftimedatefullshort', 'langconfig'));
            $message = new \core\message\message();
            $message->component = 'local_odoo_sync';
            $message->name = 'license_expiry_reminder';
            $message->userfrom = $fromuser;
            $message->userto = $userto;
            $message->subject = get_string('license_expiry_reminder_subject', 'local_odoo_sync');
            $message->fullmessage = get_string('license_expiry_reminder_body', 'local_odoo_sync', $expirydate);
            $message->fullmessageformat = FORMAT_PLAIN;
            $message->smallmessage = get_string('license_expiry_reminder_body', 'local_odoo_sync', $expirydate);
            $message->notification = 1;
            message_send($message);
        }
    }
}
