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

namespace local_studentlife;

defined('MOODLE_INTERNAL') || die();

final class observer {
    public static function forum_discussion_created(\mod_forum\event\discussion_created $event): void {
        global $DB;

        $courseid = (int)$event->courseid;
        $discussionid = (int)$event->objectid;

        // Find users who enabled announcements for this course.
        $prefs = $DB->get_records('local_studentlife_notifpref', ['courseid' => $courseid, 'announcements' => 1]);
        if (!$prefs) {
            return;
        }

        $url = (new \moodle_url('/mod/forum/discuss.php', ['d' => $discussionid]))->out(false);
        $subject = $event->get_name();

        foreach ($prefs as $p) {
            \local_studentlife\local\notification_repository::queue_digest_item((int)$p->userid, $courseid, 'announcement', $subject, $url);
        }
    }

    public static function assign_submission_graded(\mod_assign\event\submission_graded $event): void {
        $userid = (int)$event->relateduserid;
        $courseid = (int)$event->courseid;
        if ($userid <= 0 || $courseid <= 0) {
            return;
        }

        // Respect per-course preference (defaults are handled by UI insert on first save).
        $pref = self::get_pref($userid, $courseid);
        if (!$pref || empty($pref->grades)) {
            return;
        }

        $url = $event->get_url()->out(false);
        \local_studentlife\local\notification_repository::queue_digest_item($userid, $courseid, 'grade', $event->get_name(), $url);
    }

    public static function quiz_grade_updated(\mod_quiz\event\quiz_grade_updated $event): void {
        $userid = (int)$event->relateduserid;
        $courseid = (int)$event->courseid;
        if ($userid <= 0 || $courseid <= 0) {
            return;
        }
        $pref = self::get_pref($userid, $courseid);
        if (!$pref || empty($pref->grades)) {
            return;
        }

        $url = $event->get_url()->out(false);
        \local_studentlife\local\notification_repository::queue_digest_item($userid, $courseid, 'grade', $event->get_name(), $url);
    }

    private static function get_pref(int $userid, int $courseid): ?\stdClass {
        global $DB;
        return $DB->get_record('local_studentlife_notifpref', ['userid' => $userid, 'courseid' => $courseid]);
    }
}

