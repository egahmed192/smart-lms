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
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_parent_portal');

require_capability('local/parent_portal:manage_relationships', context_system::instance());

$PAGE->set_url(new moodle_url('/local/parent_portal/edit.php'));
$PAGE->set_title(get_string('add_relationship', 'local_parent_portal'));
$PAGE->set_heading(get_string('add_relationship', 'local_parent_portal'));

$mform = new \local_parent_portal\form\edit_relationship_form();
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/parent_portal/index.php'));
}
if ($data = $mform->get_data()) {
    if ($DB->record_exists('local_parent_portal_rel', ['parent_userid' => $data->parent_userid, 'student_userid' => $data->student_userid, 'active' => 1])) {
        \core\notification::warning(get_string('relationship_exists', 'local_parent_portal'));
    } else {
        $DB->insert_record('local_parent_portal_rel', (object)[
            'parent_userid' => $data->parent_userid,
            'student_userid' => $data->student_userid,
            'active' => 1,
            'timecreated' => time(),
            'source' => 'manual',
        ]);
        redirect(new moodle_url('/local/parent_portal/index.php'), get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
    }
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
