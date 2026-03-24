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

final class planner_repository {
    public static function get_tasks_for_user(int $userid, ?int $courseid = null): array {
        global $DB;

        $params = ['userid' => $userid];
        $wheres = ['userid = :userid'];

        if ($courseid !== null) {
            $params['courseid'] = $courseid;
            $wheres[] = 'courseid = :courseid';
        }

        $where = implode(' AND ', $wheres);
        $orderby = 'completed ASC, duetime ASC, timemodified DESC';

        return $DB->get_records_select('local_studentlife_task', $where, $params, $orderby);
    }

    public static function get_task(int $id, int $userid): \stdClass {
        global $DB;

        return $DB->get_record('local_studentlife_task', ['id' => $id, 'userid' => $userid], '*', MUST_EXIST);
    }

    public static function upsert_task(\stdClass $record): int {
        global $DB;

        if (!empty($record->id)) {
            $DB->update_record('local_studentlife_task', $record);
            return (int)$record->id;
        }

        return (int)$DB->insert_record('local_studentlife_task', $record);
    }

    public static function delete_task(int $id, int $userid): void {
        global $DB;
        $DB->delete_records('local_studentlife_task', ['id' => $id, 'userid' => $userid]);
    }

    public static function set_completed(int $id, int $userid, bool $completed): void {
        global $DB;

        $task = self::get_task($id, $userid);
        $task->completed = $completed ? 1 : 0;
        $task->timecompleted = $completed ? time() : null;
        $task->timemodified = time();
        $DB->update_record('local_studentlife_task', $task);
    }

    public static function get_progress_by_course(int $userid): array {
        global $DB;

        $sql = "SELECT courseid,
                       COUNT(1) AS total,
                       SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) AS completed
                  FROM {local_studentlife_task}
                 WHERE userid = ?
                   AND courseid IS NOT NULL
              GROUP BY courseid";
        $rows = $DB->get_records_sql($sql, [$userid]);

        $out = [];
        foreach ($rows as $r) {
            $out[(int)$r->courseid] = [
                'total' => (int)$r->total,
                'completed' => (int)$r->completed,
            ];
        }
        return $out;
    }
}

