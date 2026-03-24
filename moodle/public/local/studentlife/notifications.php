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

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/course/lib.php');

require_login();

$url = new moodle_url('/local/studentlife/notifications.php');
$PAGE->set_url($url);
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_title(get_string('notifications', 'local_studentlife'));
$PAGE->set_heading(get_string('notifications:heading', 'local_studentlife'));

$enrolled = course_get_enrolled_courses_for_logged_in_user(0, 0, null, 'id,fullname,shortname');
$prefs = \local_studentlife\local\notification_repository::get_prefs_for_user($USER->id);
$prefsbycourse = [];
foreach ($prefs as $p) {
    $prefsbycourse[(int)$p->courseid] = $p;
}

if (optional_param('save', 0, PARAM_BOOL)) {
    require_sesskey();

    foreach ($enrolled as $c) {
        $cid = (int)$c->id;
        $deadlines = optional_param('deadlines_' . $cid, 0, PARAM_BOOL) ? 1 : 0;
        $announcements = optional_param('announcements_' . $cid, 0, PARAM_BOOL) ? 1 : 0;
        $grades = optional_param('grades_' . $cid, 0, PARAM_BOOL) ? 1 : 0;
        $digest = optional_param('digest_' . $cid, 'daily', PARAM_ALPHA);
        if (!in_array($digest, ['none', 'daily', 'weekly'], true)) {
            $digest = 'daily';
        }

        \local_studentlife\local\notification_repository::upsert_pref($USER->id, $cid, [
            'deadlines' => $deadlines,
            'announcements' => $announcements,
            'grades' => $grades,
            'digest' => $digest,
        ]);
    }

    redirect($url, get_string('changessaved'));
}

$rows = [];
foreach ($enrolled as $c) {
    $cid = (int)$c->id;
    $p = $prefsbycourse[$cid] ?? null;
    $rows[] = [
        'courseid' => $cid,
        'coursename' => format_string($c->fullname),
        'deadlines' => $p ? (int)$p->deadlines : 1,
        'announcements' => $p ? (int)$p->announcements : 1,
        'grades' => $p ? (int)$p->grades : 1,
        'digest' => $p ? (string)$p->digest : 'daily',
    ];
}

echo $OUTPUT->header();

echo html_writer::start_tag('form', ['method' => 'post', 'action' => $url->out(false)]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'save', 'value' => 1]);

echo html_writer::start_div('table-responsive');
echo html_writer::start_tag('table', ['class' => 'table table-sm']);
echo html_writer::start_tag('thead');
echo html_writer::tag('tr',
    html_writer::tag('th', 'Course') .
    html_writer::tag('th', get_string('notifications:deadlines', 'local_studentlife')) .
    html_writer::tag('th', get_string('notifications:announcements', 'local_studentlife')) .
    html_writer::tag('th', get_string('notifications:grades', 'local_studentlife')) .
    html_writer::tag('th', get_string('notifications:digest', 'local_studentlife'))
);
echo html_writer::end_tag('thead');
echo html_writer::start_tag('tbody');

foreach ($rows as $r) {
    $cid = $r['courseid'];
    $digestselect = html_writer::select(
        [
            'none' => get_string('notifications:digest:none', 'local_studentlife'),
            'daily' => get_string('notifications:digest:daily', 'local_studentlife'),
            'weekly' => get_string('notifications:digest:weekly', 'local_studentlife'),
        ],
        'digest_' . $cid,
        $r['digest'],
        false,
        ['class' => 'custom-select custom-select-sm']
    );

    echo html_writer::tag('tr',
        html_writer::tag('td', $r['coursename']) .
        html_writer::tag('td', html_writer::checkbox('deadlines_' . $cid, 1, (bool)$r['deadlines'], '')) .
        html_writer::tag('td', html_writer::checkbox('announcements_' . $cid, 1, (bool)$r['announcements'], '')) .
        html_writer::tag('td', html_writer::checkbox('grades_' . $cid, 1, (bool)$r['grades'], '')) .
        html_writer::tag('td', $digestselect)
    );
}

echo html_writer::end_tag('tbody');
echo html_writer::end_tag('table');
echo html_writer::end_div();

echo html_writer::tag('button', get_string('savechanges'), ['type' => 'submit', 'class' => 'btn btn-primary']);
echo html_writer::end_tag('form');

echo $OUTPUT->footer();

