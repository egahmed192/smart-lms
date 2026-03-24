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

$courseid = required_param('courseid', PARAM_INT);
$build = optional_param('build', 0, PARAM_BOOL);
$force = optional_param('force', 0, PARAM_BOOL);
$includeindex = optional_param('includeindex', 1, PARAM_BOOL);

$course = get_course($courseid);
require_login($course);

$context = context_course::instance($courseid);
require_capability('moodle/course:view', $context);

$url = new moodle_url('/local/studentlife/offlinepack.php', ['courseid' => $courseid]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout('course');
$PAGE->set_title(get_string('offlinepack', 'local_studentlife'));
$PAGE->set_heading($course->fullname);

$existing = \local_studentlife\local\offline_pack_service::get_existing_pack($USER->id, $courseid);

// If requested, (re)build or queue.
if ($build) {
    require_sesskey();
    if ($force) {
        $existing = null;
    }

    // Decide whether to run async based on file count.
    // (Keeps UI responsive for large courses.)
    $modinfo = get_fast_modinfo($course);
    $approxfiles = 0;
    foreach ($modinfo->get_cms() as $cm) {
        if ($cm->uservisible && in_array($cm->modname, ['resource', 'folder'], true)) {
            $approxfiles++;
        }
    }

    if ($approxfiles >= 75) {
        $task = new \local_studentlife\task\build_offline_pack();
        $task->set_custom_data([
            'userid' => $USER->id,
            'courseid' => $courseid,
            'includeindex' => $includeindex ? 1 : 0,
        ]);
        \core\task\manager::queue_adhoc_task($task);
        redirect($url, get_string('offlinepack:queued', 'local_studentlife'), null, \core\output\notification::NOTIFY_INFO);
    } else {
        core_php_time_limit::raise();
        raise_memory_limit(MEMORY_EXTRA);
        $existing = \local_studentlife\local\offline_pack_service::build_and_store_pack($USER->id, $courseid, $includeindex);
        redirect($url, get_string('offlinepack:ready', 'local_studentlife'), null, \core\output\notification::NOTIFY_SUCCESS);
    }
}

$downloadurl = null;
if ($existing) {
    $downloadurl = moodle_url::make_pluginfile_url(
        context_user::instance($USER->id)->id,
        'local_studentlife',
        \local_studentlife\local\offline_pack_service::FILEAREA_OFFLINEPACK,
        $courseid,
        '/',
        $existing->get_filename()
    )->out(false);
}

echo $OUTPUT->header();

echo $OUTPUT->box_start('generalbox');
echo html_writer::tag('h3', get_string('offlinepack', 'local_studentlife'), ['class' => 'h5']);

if ($downloadurl) {
    echo $OUTPUT->notification(get_string('offlinepack:ready', 'local_studentlife'), 'notifysuccess');
    echo html_writer::link($downloadurl, get_string('offlinepack:download', 'local_studentlife'), ['class' => 'btn btn-primary']);
    echo html_writer::div('', 'mt-2');
    echo html_writer::link(new moodle_url('/local/studentlife/offlinepack.php', [
        'courseid' => $courseid,
        'build' => 1,
        'force' => 1,
        'includeindex' => 1,
        'sesskey' => sesskey(),
    ]), 'Rebuild pack', ['class' => 'btn btn-outline-secondary btn-sm mt-2']);
} else {
    echo $OUTPUT->notification('No offline pack built yet.', 'info');
    echo html_writer::link(new moodle_url('/local/studentlife/offlinepack.php', [
        'courseid' => $courseid,
        'build' => 1,
        'includeindex' => 1,
        'sesskey' => sesskey(),
    ]), get_string('offlinepack:build', 'local_studentlife'), ['class' => 'btn btn-primary']);
}

echo $OUTPUT->box_end();

echo $OUTPUT->footer();

