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

require_login();

$url = new moodle_url('/local/studentlife/today.php');
$PAGE->set_url($url);
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_title(get_string('today', 'local_studentlife'));
$PAGE->set_heading(get_string('today:heading', 'local_studentlife'));

$cache = cache::make('local_studentlife', 'today');
$cachekey = $USER->id . ':' . usergetmidnight(time());

$data = $cache->get($cachekey);
if ($data === false) {
    $data = \local_studentlife\local\today_context_builder::build($USER->id, $OUTPUT);
    $cache->set($cachekey, $data);
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_studentlife/today', $data);
echo $OUTPUT->footer();
