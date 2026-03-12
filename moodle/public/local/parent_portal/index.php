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

$PAGE->set_url(new moodle_url('/local/parent_portal/index.php'));
$PAGE->set_title(get_string('relationship_list', 'local_parent_portal'));
$PAGE->set_heading(get_string('relationship_list', 'local_parent_portal'));

$search = optional_param('search', '', PARAM_RAW);
$page = optional_param('page', 0, PARAM_INT);
$perpage = 20;

$sql = "SELECT r.id, r.parent_userid, r.student_userid, r.source, r.timecreated,
               pu.firstname AS parent_firstname, pu.lastname AS parent_lastname, pu.email AS parent_email,
               su.firstname AS student_firstname, su.lastname AS student_lastname, su.email AS student_email
          FROM {local_parent_portal_rel} r
          JOIN {user} pu ON pu.id = r.parent_userid
          JOIN {user} su ON su.id = r.student_userid
         WHERE r.active = 1";
$params = [];
if ($search !== '') {
    $sql .= " AND (pu.firstname LIKE :s1 OR pu.lastname LIKE :s2 OR pu.email LIKE :s3 OR su.firstname LIKE :s4 OR su.lastname LIKE :s5 OR su.email LIKE :s6)";
    $like = '%' . $DB->sql_like_escape($search) . '%';
    $params = ['s1' => $like, 's2' => $like, 's3' => $like, 's4' => $like, 's5' => $like, 's6' => $like];
}
$sql .= " ORDER BY r.timecreated DESC";

$total = $DB->count_records_sql("SELECT COUNT(*) FROM ({$sql}) c", $params);
$relationships = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

$mform = new \local_parent_portal\form\relationship_form(null, ['search' => $search]);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/parent_portal/index.php'));
}
if ($data = $mform->get_data()) {
    if (!empty($data->search)) {
        redirect(new moodle_url('/local/parent_portal/index.php', ['search' => $data->search]));
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('relationship_list', 'local_parent_portal'));
$mform->display();

$table = new html_table();
$table->head = [get_string('parent', 'local_parent_portal'), get_string('student', 'local_parent_portal'), 'Source', 'Actions'];
$table->data = [];
foreach ($relationships as $rel) {
    $parentname = fullname((object)['firstname' => $rel->parent_firstname, 'lastname' => $rel->parent_lastname]) . ' (' . $rel->parent_email . ')';
    $studentname = fullname((object)['firstname' => $rel->student_firstname, 'lastname' => $rel->student_lastname]) . ' (' . $rel->student_email . ')';
    $deleteurl = new moodle_url('/local/parent_portal/index.php', ['delete' => $rel->id, 'sesskey' => sesskey()]);
    $table->data[] = [
        $parentname,
        $studentname,
        $rel->source,
        $OUTPUT->action_link($deleteurl, get_string('delete'), null, ['class' => 'btn btn-secondary btn-sm']),
    ];
}
if (!empty($table->data)) {
    echo html_writer::table($table);
    echo $OUTPUT->paging_bar($total, $page, $perpage, new moodle_url('/local/parent_portal/index.php', ['search' => $search]));
} else {
    echo $OUTPUT->notification(get_string('no_relationships', 'local_parent_portal'), 'info');
}

$addurl = new moodle_url('/local/parent_portal/edit.php');
echo $OUTPUT->single_button($addurl, get_string('add_relationship', 'local_parent_portal'), 'get');

echo $OUTPUT->footer();
