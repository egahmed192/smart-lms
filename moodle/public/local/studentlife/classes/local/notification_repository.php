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

namespace local_studentlife\local;

defined('MOODLE_INTERNAL') || die();

final class notification_repository {
    public static function get_prefs_for_user(int $userid): array {
        global $DB;
        return $DB->get_records('local_studentlife_notifpref', ['userid' => $userid], 'courseid ASC');
    }

    public static function upsert_pref(int $userid, int $courseid, array $values): void {
        global $DB;
        $now = time();
        $rec = $DB->get_record('local_studentlife_notifpref', ['userid' => $userid, 'courseid' => $courseid]);
        $data = (object)array_merge([
            'userid' => $userid,
            'courseid' => $courseid,
            'deadlines' => 1,
            'announcements' => 1,
            'grades' => 1,
            'digest' => 'daily',
            'timemodified' => $now,
        ], $values);
        $data->timemodified = $now;

        if ($rec) {
            $data->id = $rec->id;
            $DB->update_record('local_studentlife_notifpref', $data);
        } else {
            $DB->insert_record('local_studentlife_notifpref', $data);
        }
    }

    public static function queue_digest_item(int $userid, ?int $courseid, string $type, string $subject, ?string $url): void {
        global $DB;
        $DB->insert_record('local_studentlife_digest_item', (object)[
            'userid' => $userid,
            'courseid' => $courseid,
            'type' => $type,
            'subject' => $subject,
            'url' => $url,
            'timecreated' => time(),
        ]);
    }

    public static function get_users_with_digest(): array {
        global $DB;
        $sql = "SELECT DISTINCT userid
                  FROM {local_studentlife_notifpref}
                 WHERE digest <> ?";
        $rows = $DB->get_records_sql($sql, ['none']);
        return array_map('intval', array_keys($rows));
    }

    public static function get_digest_items(int $userid, int $since): array {
        global $DB;
        $sql = "SELECT *
                  FROM {local_studentlife_digest_item}
                 WHERE userid = ?
                   AND timecreated >= ?
              ORDER BY timecreated DESC";
        return $DB->get_records_sql($sql, [$userid, $since]);
    }

    public static function cleanup_digest_items(int $before): void {
        global $DB;
        $DB->delete_records_select('local_studentlife_digest_item', 'timecreated < ?', [$before]);
    }
}

