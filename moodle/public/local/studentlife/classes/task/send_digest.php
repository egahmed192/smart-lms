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

use core_calendar\local\api as calendar_api;

final class send_digest extends \core\task\scheduled_task {
    public function get_name(): string {
        return 'Student life digest';
    }

    public function execute(): void {
        global $DB;

        $now = time();
        $users = \local_studentlife\local\notification_repository::get_users_with_digest();

        foreach ($users as $userid) {
            $user = \core_user::get_user($userid, '*', IGNORE_MISSING);
            if (!$user || !\core_user::is_real_user($userid)) {
                continue;
            }

            $prefs = \local_studentlife\local\notification_repository::get_prefs_for_user($userid);
            if (!$prefs) {
                continue;
            }

            // Decide digest cadence per user (take the most common non-none setting).
            $digest = 'none';
            foreach ($prefs as $p) {
                if (!empty($p->digest) && $p->digest !== 'none') {
                    $digest = $p->digest;
                    break;
                }
            }
            if ($digest === 'none') {
                continue;
            }
            if ($digest === 'weekly') {
                // Only send weekly digest on Mondays.
                if ((int)date('N', $now) !== 1) {
                    continue;
                }
            }

            $window = $digest === 'weekly' ? (7 * DAYSECS) : DAYSECS;
            $since = $now - $window;

            $items = \local_studentlife\local\notification_repository::get_digest_items($userid, $since);

            // Also include upcoming deadlines from enabled courses.
            $courseids = [];
            foreach ($prefs as $p) {
                if (!empty($p->deadlines)) {
                    $courseids[] = (int)$p->courseid;
                }
            }
            $courseids = array_values(array_unique(array_filter($courseids)));

            $deadlines = [];
            if (!empty($courseids)) {
                $midnight = usergetmidnight($now, $user->timezone);
                $end = $midnight + ($digest === 'weekly' ? (7 * DAYSECS) : (2 * DAYSECS));
                $events = calendar_api::get_action_events_by_timesort(
                    timesortfrom: $midnight,
                    timesortto: $end,
                    aftereventid: null,
                    limitnum: 20,
                    limittononsuspendedevents: true,
                    user: $user
                );
                foreach ($events as $e) {
                    $course = $e->get_course();
                    $cid = $course ? (int)$course->get('id') : 0;
                    if ($cid && in_array($cid, $courseids, true)) {
                        $deadlines[] = $e;
                    }
                }
            }

            if (empty($items) && empty($deadlines)) {
                continue;
            }

            $subject = get_string('digest:subject', 'local_studentlife');
            $fulltext = $subject . "\n\n";
            $fullhtml = '<h2>' . s($subject) . '</h2>';

            if (!empty($deadlines)) {
                $fulltext .= "Upcoming deadlines:\n";
                $fullhtml .= '<h3>Upcoming deadlines</h3><ul>';
                foreach ($deadlines as $e) {
                    $name = $e->get_name();
                    $ts = $e->get_times()->get_start_time()->getTimestamp();
                    $when = userdate($ts, '', $user->timezone);
                    $fulltext .= "- {$name} ({$when})\n";
                    $fullhtml .= '<li>' . s($name) . ' <span style="color:#666">(' . s($when) . ')</span></li>';
                }
                $fulltext .= "\n";
                $fullhtml .= '</ul>';
            }

            if (!empty($items)) {
                $fulltext .= "Updates:\n";
                $fullhtml .= '<h3>Updates</h3><ul>';
                foreach ($items as $it) {
                    $line = $it->subject;
                    if (!empty($it->url)) {
                        $line .= ' - ' . $it->url;
                    }
                    $fulltext .= "- {$line}\n";
                    $fullhtml .= '<li>' . s($it->subject) .
                        (!empty($it->url) ? ' - <a href="' . s($it->url) . '">Open</a>' : '') . '</li>';
                }
                $fulltext .= "\n";
                $fullhtml .= '</ul>';
            }

            $msg = new \core\message\message();
            $msg->component = 'local_studentlife';
            $msg->name = 'digest';
            $msg->userfrom = \core_user::get_noreply_user();
            $msg->userto = $user;
            $msg->subject = $subject;
            $msg->fullmessage = $fulltext;
            $msg->fullmessageformat = FORMAT_PLAIN;
            $msg->fullmessagehtml = $fullhtml;
            $msg->smallmessage = $subject;
            $msg->notification = 1;

            message_send($msg);
        }

        // Cleanup old items (keep ~8 days).
        \local_studentlife\local\notification_repository::cleanup_digest_items($now - (8 * DAYSECS));
    }
}

