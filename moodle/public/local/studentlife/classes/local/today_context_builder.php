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

use core_calendar\external\events_exporter;
use core_calendar\external\events_related_objects_cache;
use core_calendar\local\api as calendar_api;
use renderer_base;

require_once($CFG->dirroot . '/course/lib.php');

final class today_context_builder {
    /**
     * Build the Today page context for templates.
     *
     * @param int $userid
     * @param renderer_base $output
     * @return array
     */
    public static function build(int $userid, renderer_base $output): array {
        $user = \core_user::get_user($userid, '*', MUST_EXIST);

        $midnight = usergetmidnight(time(), $user->timezone);
        $weekend = $midnight + (7 * DAYSECS);

        $actionevents = calendar_api::get_action_events_by_timesort(
            timesortfrom: $midnight,
            timesortto: $weekend,
            aftereventid: null,
            limitnum: 20,
            limittononsuspendedevents: true,
            user: $user
        );
        $actioneventscontext = self::export_events($actionevents, $output);

        $unreadcounts = \core_message\api::get_unread_conversation_counts($userid);
        $unreadtotal = (int)($unreadcounts['favourites'] ?? 0);
        foreach (($unreadcounts['types'] ?? []) as $count) {
            $unreadtotal += (int)$count;
        }

        $timetablecategoryid = (int)get_config('local_studentlife', 'timetablecategoryid');
        $timetableevents = [];
        if ($timetablecategoryid > 0) {
            $timestartfrom = $midnight;
            $timestartto = $midnight + DAYSECS;
            $timetableevents = calendar_api::get_events(
                timestartfrom: $timestartfrom,
                timestartto: $timestartto,
                timesortfrom: $timestartfrom,
                timesortto: $timestartto,
                limitnum: 50,
                categoriesfilter: [$timetablecategoryid],
            );
        }
        $timetablecontext = self::export_events($timetableevents, $output);

        return [
            'generatedat' => userdate(time(), '', $user->timezone),
            'unreadmessagescount' => $unreadtotal,
            'deadlines' => [
                'events' => $actioneventscontext['events'],
                'has' => !empty($actioneventscontext['events']),
            ],
            'timetable' => [
                'events' => $timetablecontext['events'],
                'has' => !empty($timetablecontext['events']),
                'configured' => $timetablecategoryid > 0,
            ],
        ];
    }

    /**
     * Export events using core calendar exporters.
     *
     * @param array $events
     * @param renderer_base $output
     * @return array
     */
    private static function export_events(array $events, renderer_base $output): array {
        if (empty($events)) {
            return ['events' => []];
        }

        $cache = new events_related_objects_cache($events);
        $exporter = new events_exporter($events, ['cache' => $cache]);
        $exported = $exporter->export($output);

        // Convert exporter objects to Mustache-friendly arrays.
        $items = [];
        foreach (($exported->events ?? []) as $e) {
            $items[] = [
                'id' => $e->id ?? null,
                'name' => $e->name ?? '',
                'formattedtime' => $e->formattedtime ?? '',
                'url' => $e->url ?? ($e->viewurl ?? ''),
                'course' => isset($e->course) ? [
                    'id' => $e->course->id ?? null,
                    'fullname' => $e->course->fullname ?? '',
                    'shortname' => $e->course->shortname ?? '',
                ] : null,
                'icon' => isset($e->icon) ? [
                    'key' => $e->icon->key ?? null,
                    'component' => $e->icon->component ?? null,
                    'alttext' => $e->icon->alttext ?? '',
                ] : null,
                'isactionevent' => !empty($e->isactionevent),
                'overdue' => !empty($e->overdue),
            ];
        }

        return ['events' => $items];
    }
}

