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
require_once(__DIR__ . '/lib.php');
require_once($CFG->dirroot . '/local/odoo_sync/lib.php');

require_login(null, false);
$PAGE->set_url(new moodle_url('/local/parent_portal/dashboard.php'));
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->set_title(get_string('parent_dashboard', 'local_parent_portal'));
$PAGE->set_heading(get_string('parent_dashboard', 'local_parent_portal'));

require_capability('local/parent_portal:view_child_data', context_system::instance());

global $DB;
$studentids = local_parent_portal_get_students_for_parent($USER->id);
$students = [];
foreach ($studentids as $sid) {
    if (!local_odoo_sync_is_license_valid($sid)) {
        continue;
    }
    $u = $DB->get_record('user', ['id' => $sid, 'deleted' => 0]);
    if ($u) {
        $students[] = $u;
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('parent_dashboard', 'local_parent_portal'));

if (empty($students)) {
    echo $OUTPUT->notification(get_string('no_children', 'local_parent_portal'), 'info');
    echo $OUTPUT->footer();
    exit;
}

$items = [];
foreach ($students as $s) {
    $url = new moodle_url('/local/parent_portal/child.php', ['studentid' => $s->id]);
    $items[] = html_writer::tag('li', html_writer::link($url, fullname($s)));
}
echo html_writer::tag('ul', implode('', $items));
echo $OUTPUT->footer();
