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

defined('MOODLE_INTERNAL') || die();

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
            // Special case: "parents" are usually not enrolled themselves.
            // When filtering parents by course, interpret it as "parents of students enrolled in this course".
            if ($target === 'parents' && $DB->get_manager()->table_exists('local_parent_portal_rel')) {
                $studentids = $DB->get_fieldset_sql(
                    "SELECT DISTINCT ue.userid
                       FROM {user_enrolments} ue
                       JOIN {enrol} e ON e.id = ue.enrolid
                      WHERE e.courseid = ?",
                    [$courseid]
                );
                if (!empty($studentids)) {
                    list($sin, $sparams) = $DB->get_in_or_equal($studentids, SQL_PARAMS_QM);
                    $recipients = $DB->get_fieldset_sql(
                        "SELECT DISTINCT parent_userid
                           FROM {local_parent_portal_rel}
                          WHERE active = 1 AND student_userid {$sin}",
                        $sparams
                    );
                } else {
                    $recipients = [];
                }
            } else {
                $enrolled = $DB->get_fieldset_sql(
                    "SELECT DISTINCT ue.userid
                       FROM {user_enrolments} ue
                       JOIN {enrol} e ON e.id = ue.enrolid
                      WHERE e.courseid = ?",
                    [$courseid]
                );
                $recipients = array_intersect($recipients, $enrolled);
            }
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
        // Students in this class: from Odoo sync map (so it works even when no course is mapped).
        $studentuserids = $DB->get_fieldset_sql(
            "SELECT userid FROM {local_odoo_sync_map}
              WHERE year_apply_for_id = ? AND standard_id = ? AND odoo_type = 'student'",
            [$yearid, $standardid]
        );
        $studentuserids = $studentuserids ? array_values($studentuserids) : [];

        $courseids = $DB->get_fieldset_sql(
            "SELECT courseid FROM {local_odoo_sync_course_map} WHERE year_apply_for_id = ? AND standard_id = ?",
            [$yearid, $standardid]
        );
        $ctxids = [];
        if (!empty($courseids)) {
            list($insql, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
            $ctxids = $DB->get_fieldset_sql("SELECT id FROM {context} WHERE contextlevel = 50 AND instanceid " . $insql, $params);
            $ctxids = $ctxids ? array_values($ctxids) : [];
        }

        if ($target === 'class_students') {
            return $studentuserids;
        }
        if ($target === 'class_teachers') {
            if (empty($ctxids)) {
                return [];
            }
            $teacherids = $DB->get_fieldset_sql("SELECT id FROM {role} WHERE shortname IN ('teacher', 'editingteacher')");
            if (empty($teacherids)) {
                return [];
            }
            list($ctxin_qm, $ctxparams) = $DB->get_in_or_equal($ctxids, SQL_PARAMS_QM);
            list($rin_qm, $rparams) = $DB->get_in_or_equal($teacherids, SQL_PARAMS_QM);
            return $DB->get_fieldset_sql(
                "SELECT DISTINCT ra.userid
                   FROM {role_assignments} ra
                  WHERE ra.contextid {$ctxin_qm} AND ra.roleid {$rin_qm}",
                array_merge($ctxparams, $rparams)
            );
        }
        // class_all: students (from sync map) + all users with role in course context (teachers etc.).
        $all = $studentuserids;
        if (!empty($ctxids)) {
            list($ctxin, $params2) = $DB->get_in_or_equal($ctxids, SQL_PARAMS_NAMED);
            $courseuserids = $DB->get_fieldset_sql("SELECT DISTINCT userid FROM {role_assignments} WHERE contextid " . $ctxin, $params2);
            $all = array_values(array_unique(array_merge($all, $courseuserids ?: [])));
        }
        return $all;
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
 * Get list of all classes (year_apply_for_id-standard_id => label).
 * Includes classes from (1) course map and (2) students in sync map, so every class that exists is shown.
 * Uses Odoo year/standard display_name when available (e.g. "Grade 5 – Section A") instead of raw IDs.
 *
 * @param object $DB
 * @return array
 */
function local_message_audit_bulk_get_classes($DB): array {
    // All classes: from course map OR from students in sync map (so we show every class that has students or a mapped course).
    $sql = "SELECT DISTINCT year_apply_for_id, standard_id
              FROM (
                SELECT year_apply_for_id, standard_id FROM {local_odoo_sync_course_map}
                UNION
                SELECT year_apply_for_id, standard_id FROM {local_odoo_sync_map}
                 WHERE odoo_type = 'student' AND year_apply_for_id IS NOT NULL AND year_apply_for_id > 0
                   AND standard_id IS NOT NULL AND standard_id > 0
              ) u
             ORDER BY year_apply_for_id, standard_id";
    $rows = $DB->get_records_sql($sql);
    $years = $DB->get_records('local_odoo_sync_year', null, '', 'odoo_id, display_name');
    $standards = $DB->get_records('local_odoo_sync_standard', null, '', 'odoo_id, display_name');
    $out = [];
    foreach ($rows as $r) {
        $yid = (int)$r->year_apply_for_id;
        $sid = (int)$r->standard_id;
        $key = $yid . '-' . $sid;
        $yearname = isset($years[$yid]) && !empty($years[$yid]->display_name) ? $years[$yid]->display_name : (string)$yid;
        $standardname = isset($standards[$sid]) && !empty($standards[$sid]->display_name) ? $standards[$sid]->display_name : (string)$sid;
        $out[$key] = $yearname . ' – ' . $standardname;
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
    $rows = $DB->get_records_menu('cohort', null, 'name ASC', 'id,name');
    return $rows ?: [];
}

