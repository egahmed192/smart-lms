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

admin_externalpage_setup('local_message_audit_bulk');
require_capability('local/message_audit:send_bulk_message', context_system::instance());

$PAGE->set_url(new moodle_url('/local/message_audit/bulk.php'));
$PAGE->set_title(get_string('bulk_message', 'local_message_audit'));
$PAGE->set_heading(get_string('bulk_message', 'local_message_audit'));

global $DB;

$target = optional_param('target', 'students', PARAM_ALPHAEXT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$classkey = optional_param('classkey', '', PARAM_ALPHANUMEXT);
$cohortid = optional_param('cohortid', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);
$message = optional_param('message', '', PARAM_RAW);

$recipients = local_message_audit_bulk_get_recipients($DB, $target, $courseid, $classkey, $cohortid);
$recipients = array_values(array_unique(array_filter(array_map('intval', $recipients))));
$count = count($recipients);

if ($confirm && $message !== '' && confirm_sesskey() && $count > 0) {
    $_SESSION['local_message_audit_bulk_recipients'] = $recipients;
    $_SESSION['local_message_audit_bulk_message'] = $message;
    $_SESSION['local_message_audit_bulk_courseid'] = $courseid;
    $_SESSION['local_message_audit_bulk_target'] = $target;
    $_SESSION['local_message_audit_bulk_classkey'] = $classkey;
    $_SESSION['local_message_audit_bulk_cohortid'] = $cohortid;
    $_SESSION['local_message_audit_bulk_offset'] = 0;
    $_SESSION['local_message_audit_bulk_total_sent'] = 0;
    redirect(new moodle_url('/local/message_audit/bulk_progress.php'));
}

$classes = local_message_audit_bulk_get_classes($DB);
$cohorts = local_message_audit_bulk_get_cohorts($DB);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('bulk_message', 'local_message_audit'));

echo '<form method="get" action="bulk.php" class="mb-3">';
echo '<div class="mb-2"><label class="me-2">' . get_string('target', 'local_message_audit') . '</label>';
echo '<select name="target" id="bulk-target" class="form-select d-inline-block w-auto">';
$targets = [
    'students' => get_string('target_students', 'local_message_audit'),
    'teachers' => get_string('target_teachers', 'local_message_audit'),
    'parents' => get_string('target_parents', 'local_message_audit'),
    'all' => get_string('target_all', 'local_message_audit'),
    'class_students' => get_string('target_class_students', 'local_message_audit'),
    'class_teachers' => get_string('target_class_teachers', 'local_message_audit'),
    'class_all' => get_string('target_class_all', 'local_message_audit'),
    'cohort' => get_string('target_cohort', 'local_message_audit'),
    'parents_of_class' => get_string('target_parents_of_class', 'local_message_audit'),
];
foreach ($targets as $t => $label) {
    echo '<option value="' . s($t) . '"' . ($target === $t ? ' selected' : '') . '>' . $label . '</option>';
}
echo '</select></div>';

echo '<div class="mb-2" id="bulk-course-row"><label class="me-2">' . get_string('course') . ' (optional)</label>';
echo '<input type="number" name="courseid" class="form-control d-inline-block w-auto" value="' . (int)$courseid . '" min="0"></div>';

echo '<div class="mb-2" id="bulk-class-row" style="display:none;"><label class="me-2">' . get_string('class', 'local_message_audit') . '</label>';
echo '<select name="classkey" class="form-select d-inline-block w-auto">';
echo '<option value="">-- ' . get_string('choose') . ' --</option>';
foreach ($classes as $key => $label) {
    echo '<option value="' . s($key) . '"' . ($classkey === $key ? ' selected' : '') . '>' . s($label) . '</option>';
}
echo '</select></div>';

echo '<div class="mb-2" id="bulk-cohort-row" style="display:none;"><label class="me-2">' . get_string('cohort', 'local_message_audit') . '</label>';
echo '<select name="cohortid" class="form-select d-inline-block w-auto">';
echo '<option value="0">-- ' . get_string('choose') . ' --</option>';
foreach ($cohorts as $cid => $name) {
    echo '<option value="' . (int)$cid . '"' . ($cohortid == $cid ? ' selected' : '') . '>' . s($name) . '</option>';
}
echo '</select></div>';

echo '<button type="submit" class="btn btn-secondary">' . get_string('update_count', 'local_message_audit') . '</button>';
echo '</form>';

echo '<p class="mb-2"><strong>' . get_string('recipients', 'local_message_audit') . ': ' . $count . '</strong></p>';
if ($count === 0) {
    echo '<p class="text-muted">' . get_string('no_recipients', 'local_message_audit') . '</p>';
}

$form2 = '<form method="post" action="bulk.php">';
$form2 .= '<input type="hidden" name="target" value="' . s($target) . '">';
$form2 .= '<input type="hidden" name="courseid" value="' . (int)$courseid . '">';
$form2 .= '<input type="hidden" name="classkey" value="' . s($classkey) . '">';
$form2 .= '<input type="hidden" name="cohortid" value="' . (int)$cohortid . '">';
$form2 .= '<input type="hidden" name="confirm" value="1">';
$form2 .= '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
$form2 .= '<label class="form-label">' . get_string('message', 'local_message_audit') . '</label><br><textarea name="message" class="form-control" rows="4" cols="60" required></textarea><br>';
$form2 .= '<button type="submit" class="btn btn-primary mt-2" ' . ($count === 0 ? ' disabled' : '') . '>' . get_string('send_to_n_recipients', 'local_message_audit', $count) . '</button></form>';
echo $form2;

$PAGE->requires->js_amd_inline("
require(['jquery'], function($) {
    function bulkToggle() {
        var t = $('#bulk-target').val();
        $('#bulk-course-row').toggle(t === 'students' || t === 'teachers' || t === 'parents' || t === 'all');
        $('#bulk-class-row').toggle(t === 'class_students' || t === 'class_teachers' || t === 'class_all' || t === 'parents_of_class');
        $('#bulk-cohort-row').toggle(t === 'cohort');
    }
    $('#bulk-target').on('change', bulkToggle);
    bulkToggle();
});
");

echo $OUTPUT->footer();

/**
 * Get recipient user IDs based on target and filters. Union by userid, no duplicates.
 *
 * @param object $DB
 * @param string $target
 * @param int $courseid
 * @param string $classkey year_apply_for_id-standard_id
 * @param int $cohortid
 * @return int[]
 */
function local_message_audit_bulk_get_recipients($DB, string $target, int $courseid, string $classkey, int $cohortid): array {
    $recipients = [];
    if (in_array($target, ['students', 'teachers', 'parents', 'all'], true)) {
        if ($target === 'students') {
            $recipients = $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname = 'student'");
        } elseif ($target === 'teachers') {
            $recipients = $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname IN ('teacher', 'editingteacher')");
        } elseif ($target === 'parents') {
            $recipients = $DB->get_fieldset_sql("SELECT DISTINCT parent_userid FROM {local_parent_portal_rel} WHERE active = 1");
            if (empty($recipients)) {
                $recipients = $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname = 'parent'");
            }
        } else {
            $recipients = $DB->get_fieldset_sql("SELECT id FROM {user} WHERE deleted = 0 AND id > 1");
        }
        if ($courseid > 0) {
            $enrolled = $DB->get_fieldset_sql("SELECT userid FROM {user_enrolments} ue JOIN {enrol} e ON e.id = ue.enrolid WHERE e.courseid = ?", [$courseid]);
            $recipients = array_intersect($recipients, $enrolled);
        }
        return array_values($recipients);
    }

    if (in_array($target, ['class_students', 'class_teachers', 'class_all'], true)) {
        $parts = explode('-', $classkey, 2);
        $yearid = isset($parts[0]) ? (int)$parts[0] : 0;
        $standardid = isset($parts[1]) ? (int)$parts[1] : 0;
        if ($yearid <= 0 || $standardid <= 0) {
            return [];
        }
        $courseids = $DB->get_fieldset_sql(
            "SELECT courseid FROM {local_odoo_sync_course_map} WHERE year_apply_for_id = ? AND standard_id = ?",
            [$yearid, $standardid]
        );
        if (empty($courseids)) {
            return [];
        }
        list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $ctxids = $DB->get_fieldset_sql("SELECT id FROM {context} WHERE contextlevel = 50 AND instanceid " . $insql, $params);
        if (empty($ctxids)) {
            return [];
        }
        list($ctxin, $params2) = $DB->get_in_or_equal($ctxids, SQL_PARAMS_NAMED);
        if ($target === 'class_students') {
            $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
            if (!$studentrole) {
                return [];
            }
            $params2['roleid'] = $studentrole;
            return $DB->get_fieldset_sql("SELECT DISTINCT userid FROM {role_assignments} WHERE contextid " . $ctxin . " AND roleid = :roleid", $params2);
        }
        if ($target === 'class_teachers') {
            $teacherids = $DB->get_fieldset_sql("SELECT id FROM {role} WHERE shortname IN ('teacher', 'editingteacher')");
            if (empty($teacherids)) {
                return [];
            }
            list($rin, $params3) = $DB->get_in_or_equal($teacherids, SQL_PARAMS_NAMED);
            return $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra WHERE ra.contextid " . $ctxin . " AND ra.roleid " . $rin, array_merge($params2, $params3));
        }
        return $DB->get_fieldset_sql("SELECT DISTINCT ra.userid FROM {role_assignments} ra WHERE ra.contextid " . $ctxin, $params2);
    }

    if ($target === 'cohort') {
        if ($cohortid <= 0) {
            return [];
        }
        return $DB->get_fieldset_sql("SELECT userid FROM {cohort_members} WHERE cohortid = ?", [$cohortid]);
    }

    if ($target === 'parents_of_class') {
        $parts = explode('-', $classkey, 2);
        $yearid = isset($parts[0]) ? (int)$parts[0] : 0;
        $standardid = isset($parts[1]) ? (int)$parts[1] : 0;
        if ($yearid <= 0 || $standardid <= 0) {
            return [];
        }
        $courseids = $DB->get_fieldset_sql(
            "SELECT courseid FROM {local_odoo_sync_course_map} WHERE year_apply_for_id = ? AND standard_id = ?",
            [$yearid, $standardid]
        );
        if (empty($courseids)) {
            return [];
        }
        list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $ctxids = $DB->get_fieldset_sql("SELECT id FROM {context} WHERE contextlevel = 50 AND instanceid " . $insql, $params);
        if (empty($ctxids)) {
            return [];
        }
        $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
        if (!$studentrole) {
            return [];
        }
        list($ctxin, $params2) = $DB->get_in_or_equal($ctxids, SQL_PARAMS_NAMED);
        $params2['roleid'] = $studentrole;
        $studentids = $DB->get_fieldset_sql("SELECT DISTINCT userid FROM {role_assignments} WHERE contextid " . $ctxin . " AND roleid = :roleid", $params2);
        if (empty($studentids)) {
            return [];
        }
        list($sin, $params3) = $DB->get_in_or_equal($studentids, SQL_PARAMS_NAMED);
        return $DB->get_fieldset_sql("SELECT DISTINCT parent_userid FROM {local_parent_portal_rel} WHERE active = 1 AND student_userid " . $sin, $params3);
    }

    return [];
}

/**
 * Get list of classes (year_apply_for_id-standard_id => label) from course map.
 *
 * @param object $DB
 * @return array
 */
function local_message_audit_bulk_get_classes($DB): array {
    $rows = $DB->get_records_sql("SELECT DISTINCT year_apply_for_id, standard_id FROM {local_odoo_sync_course_map} ORDER BY year_apply_for_id, standard_id");
    $out = [];
    foreach ($rows as $r) {
        $key = (int)$r->year_apply_for_id . '-' . (int)$r->standard_id;
        $out[$key] = get_string('class_label', 'local_message_audit', (object)['year' => $r->year_apply_for_id, 'standard' => $r->standard_id]);
    }
    return $out;
}

/**
 * Get list of cohorts id => name.
 *
 * @param object $DB
 * @return array
 */
function local_message_audit_bulk_get_cohorts($DB): array {
    $recs = $DB->get_records_sql("SELECT id, name FROM {cohort} ORDER BY name");
    $out = [];
    foreach ($recs as $r) {
        $out[$r->id] = $r->name;
    }
    return $out;
}
