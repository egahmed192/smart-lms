<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_odoo_sync_course_map');
require_capability('local/odoo_sync:manage', context_system::instance());

$PAGE->set_url(new moodle_url('/local/odoo_sync/course_map.php'));
$PAGE->set_title(get_string('course_map', 'local_odoo_sync'));
$PAGE->set_heading(get_string('course_map_heading', 'local_odoo_sync'));

global $DB;

// Delete mapping.
$deleteid = optional_param('delete', 0, PARAM_INT);
if ($deleteid > 0 && confirm_sesskey()) {
    $DB->delete_records('local_odoo_sync_course_map', ['id' => $deleteid]);
    redirect(new moodle_url('/local/odoo_sync/course_map.php'), get_string('mapping_removed', 'local_odoo_sync'), null, \core\output\notification::NOTIFY_SUCCESS);
}

// Add mapping.
$addcourse = optional_param('addcourse', 0, PARAM_INT);
$addyear = optional_param('addyear', 0, PARAM_INT);
$addstandard = optional_param('addstandard', 0, PARAM_INT);
if ($addcourse > 0 && $addyear > 0 && $addstandard > 0 && confirm_sesskey()) {
    $exists = $DB->get_record('local_odoo_sync_course_map', ['courseid' => $addcourse]);
    if (!$exists) {
        $DB->insert_record('local_odoo_sync_course_map', (object)[
            'courseid' => $addcourse,
            'year_apply_for_id' => $addyear,
            'standard_id' => $addstandard,
            'timemodified' => time(),
        ]);
        redirect(new moodle_url('/local/odoo_sync/course_map.php'), get_string('mapping_added', 'local_odoo_sync'), null, \core\output\notification::NOTIFY_SUCCESS);
    }
}

$years = $DB->get_records('local_odoo_sync_year', null, 'odoo_id ASC');
$standards = $DB->get_records('local_odoo_sync_standard', null, 'odoo_id ASC');
$mappedcourseids = $DB->get_fieldset_sql("SELECT courseid FROM {local_odoo_sync_course_map}");
$allcourses = $DB->get_records_sql(
    "SELECT id, fullname, shortname FROM {course} WHERE id != ? ORDER BY fullname ASC",
    [SITEID]
);
$mappings = $DB->get_records_sql(
    "SELECT m.id, m.courseid, m.year_apply_for_id, m.standard_id, c.fullname, c.shortname
       FROM {local_odoo_sync_course_map} m
       JOIN {course} c ON c.id = m.courseid
       ORDER BY m.year_apply_for_id, m.standard_id"
);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('course_map_heading', 'local_odoo_sync'));
echo '<p class="text-muted">' . get_string('course_map_intro', 'local_odoo_sync') . '</p>';

// Synced years and standards (read-only summary).
echo $OUTPUT->heading(get_string('synced_years', 'local_odoo_sync'), 3);
if (empty($years)) {
    echo '<p class="text-muted">' . get_string('no_years_standards', 'local_odoo_sync') . '</p>';
} else {
    $list = [];
    foreach ($years as $y) {
        $list[] = s($y->display_name) . ' <small>(id ' . (int)$y->odoo_id . ')</small>';
    }
    echo '<p>' . implode(' &middot; ', $list) . '</p>';
}

echo $OUTPUT->heading(get_string('synced_standards', 'local_odoo_sync'), 3);
if (empty($standards)) {
    echo '<p class="text-muted">' . get_string('no_years_standards', 'local_odoo_sync') . '</p>';
} else {
    $list = [];
    foreach ($standards as $st) {
        $list[] = s($st->display_name) . ' <small>(id ' . (int)$st->odoo_id . ')</small>';
    }
    echo '<p>' . implode(' &middot; ', $list) . '</p>';
}

// Current mappings table.
echo $OUTPUT->heading(get_string('course_map', 'local_odoo_sync'), 3);
if (empty($mappings)) {
    echo '<p>No course mappings yet. Add one below.</p>';
} else {
    $table = new \html_table();
    $table->head = [
        get_string('moodle_course', 'local_odoo_sync'),
        get_string('odoo_year', 'local_odoo_sync'),
        get_string('odoo_standard', 'local_odoo_sync'),
        '',
    ];
    $yearnames = [];
    foreach ($years as $y) {
        $yearnames[$y->odoo_id] = $y->display_name;
    }
    $standardnames = [];
    foreach ($standards as $st) {
        $standardnames[$st->odoo_id] = $st->display_name;
    }
    foreach ($mappings as $m) {
        $delurl = new moodle_url('/local/odoo_sync/course_map.php', ['delete' => $m->id, 'sesskey' => sesskey()]);
        $table->data[] = [
            s($m->fullname) . ' <small>(' . s($m->shortname) . ')</small>',
            s($yearnames[$m->year_apply_for_id] ?? 'id ' . $m->year_apply_for_id),
            s($standardnames[$m->standard_id] ?? 'id ' . $m->standard_id),
            $OUTPUT->action_link($delurl, get_string('delete_mapping', 'local_odoo_sync'), null, ['class' => 'btn btn-sm btn-outline-danger']),
        ];
    }
    echo \html_writer::table($table);
}

// Add form.
echo $OUTPUT->heading(get_string('add_mapping', 'local_odoo_sync'), 3);
$unmappedcourses = array_diff_key($allcourses, array_flip($mappedcourseids));
if (empty($years) || empty($standards)) {
    echo '<p class="text-muted">Sync years and standards first (run Odoo sync task).</p>';
} elseif (empty($unmappedcourses)) {
    echo '<p class="text-muted">All courses are already mapped. Create a new Moodle course to map.</p>';
} else {
    $actionurl = new moodle_url('/local/odoo_sync/course_map.php');
    echo '<form method="post" action="' . $actionurl->out(false) . '" class="form-inline">';
    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';
    echo '<select name="addcourse" class="custom-select mr-2" required>';
    echo '<option value="">' . get_string('moodle_course', 'local_odoo_sync') . '...</option>';
    foreach ($unmappedcourses as $c) {
        echo '<option value="' . (int)$c->id . '">' . s($c->fullname) . ' (' . s($c->shortname) . ')</option>';
    }
    echo '</select>';
    echo ' <select name="addyear" class="custom-select mr-2" required>';
    echo '<option value="">' . get_string('odoo_year', 'local_odoo_sync') . '...</option>';
    foreach ($years as $y) {
        echo '<option value="' . (int)$y->odoo_id . '">' . s($y->display_name) . '</option>';
    }
    echo '</select>';
    echo ' <select name="addstandard" class="custom-select mr-2" required>';
    echo '<option value="">' . get_string('odoo_standard', 'local_odoo_sync') . '...</option>';
    foreach ($standards as $st) {
        echo '<option value="' . (int)$st->odoo_id . '">' . s($st->display_name) . '</option>';
    }
    echo '</select>';
    echo ' <button type="submit" class="btn btn-primary">' . get_string('add_mapping', 'local_odoo_sync') . '</button>';
    echo '</form>';
}

echo '<p class="mt-4"><a href="' . $CFG->wwwroot . '/local/odoo_sync/status.php" class="btn btn-link">' . get_string('sync_status', 'local_odoo_sync') . '</a> ';
echo '<a href="' . $CFG->wwwroot . '/admin/settings.php?section=local_odoo_sync" class="btn btn-link">' . get_string('odoo_sync_settings', 'local_odoo_sync') . '</a></p>';
echo $OUTPUT->footer();
