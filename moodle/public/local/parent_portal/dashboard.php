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

// Fetch all active children for this parent.
$studentids = local_parent_portal_get_students_for_parent($USER->id);
$students = [];
if (!empty($studentids)) {
    list($insql, $params) = $DB->get_in_or_equal($studentids, SQL_PARAMS_NAMED, 'uid');
    $records = $DB->get_records_select('user', "deleted = 0 AND id $insql", $params);
    foreach ($records as $u) {
        // Skip children whose license is not valid.
        if (!local_odoo_sync_is_license_valid($u->id)) {
            continue;
        }
        $students[$u->id] = $u;
    }
}

// Preload class/grade info from Odoo mapping tables.
$classinfo = [];
if (!empty($students)) {
    $studentids = array_keys($students);
    list($insql, $params) = $DB->get_in_or_equal($studentids, SQL_PARAMS_NAMED, 'sid');
    $maps = $DB->get_records_select('local_odoo_sync_map', "odoo_type = 'student' AND userid $insql", $params);

    // Load all referenced years and standards in one go.
    $yearids = [];
    $standardids = [];
    foreach ($maps as $m) {
        if (!empty($m->year_apply_for_id)) {
            $yearids[] = (int)$m->year_apply_for_id;
        }
        if (!empty($m->standard_id)) {
            $standardids[] = (int)$m->standard_id;
        }
    }
    $yearnames = [];
    if (!empty($yearids)) {
        $yearids = array_unique($yearids);
        list($insql, $params) = $DB->get_in_or_equal($yearids, SQL_PARAMS_NAMED, 'yid');
        $years = $DB->get_records_select('local_odoo_sync_year', "odoo_id $insql", $params);
        foreach ($years as $y) {
            $yearnames[$y->odoo_id] = $y->display_name;
        }
    }
    $standardnames = [];
    if (!empty($standardids)) {
        $standardids = array_unique($standardids);
        list($insql, $params) = $DB->get_in_or_equal($standardids, SQL_PARAMS_NAMED, 'sid2');
        $standards = $DB->get_records_select('local_odoo_sync_standard', "odoo_id $insql", $params);
        foreach ($standards as $st) {
            $standardnames[$st->odoo_id] = $st->display_name;
        }
    }

    foreach ($maps as $m) {
        $userid = (int)$m->userid;
        $yearname = '';
        $standardname = '';
        if (!empty($m->year_apply_for_id) && isset($yearnames[$m->year_apply_for_id])) {
            $yearname = $yearnames[$m->year_apply_for_id];
        }
        if (!empty($m->standard_id) && isset($standardnames[$m->standard_id])) {
            $standardname = $standardnames[$m->standard_id];
        }
        $classinfo[$userid] = [
            'year' => $yearname,
            'standard' => $standardname,
        ];
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('parent_dashboard', 'local_parent_portal'));
echo html_writer::tag('p', get_string('parent_dashboard_intro', 'local_parent_portal'), ['class' => 'text-muted']);

if (empty($students)) {
    echo $OUTPUT->notification(get_string('no_children_friendly', 'local_parent_portal'), 'info');
    echo $OUTPUT->footer();
    exit;
}

// Render one card per child.
echo html_writer::start_div('row');
foreach ($students as $student) {
    $url = new moodle_url('/local/parent_portal/child.php', ['studentid' => $student->id]);
    $class = $classinfo[$student->id] ?? ['year' => '', 'standard' => ''];
    $classname = trim($class['year'] . ' ' . $class['standard']);

    $body = html_writer::tag('h5', fullname($student), ['class' => 'card-title']);
    if ($classname !== '') {
        $body .= html_writer::tag('p',
            get_string('child_class_label', 'local_parent_portal', $classname),
            ['class' => 'card-text text-muted']
        );
    }
    $body .= html_writer::tag('p',
        get_string('license_status_active', 'local_parent_portal'),
        ['class' => 'badge bg-success text-wrap']
    );
    $body .= html_writer::tag('p',
        html_writer::link($url, get_string('view_child_details', 'local_parent_portal'),
            ['class' => 'btn btn-primary']),
        []
    );

    $card = html_writer::start_div('card mb-3');
    $card .= html_writer::tag('div', $body, ['class' => 'card-body']);
    $card .= html_writer::end_div();

    echo html_writer::tag('div', $card, ['class' => 'col-md-4']);
}
echo html_writer::end_div();
echo $OUTPUT->footer();
