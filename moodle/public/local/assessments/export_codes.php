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

admin_externalpage_setup('local_assessments_export_codes');
require_capability('local/assessments:view_secret_codes', context_system::instance());

global $DB;
$users = $DB->get_records_sql(
    "SELECT u.id, u.firstname, u.lastname, u.email, s.secret_code
       FROM {user} u
       JOIN {local_assessments_student} s ON s.userid = u.id
      WHERE u.deleted = 0
   ORDER BY u.lastname, u.firstname"
);

$csv = "userid,firstname,lastname,email,secret_code\n";
foreach ($users as $u) {
    $csv .= $u->id . ',' . csv_quote($u->firstname) . ',' . csv_quote($u->lastname) . ',' . csv_quote($u->email) . ',' . $u->secret_code . "\n";
}
function csv_quote($s) {
    return '"' . str_replace('"', '""', $s) . '"';
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=secret_codes_' . date('Y-m-d') . '.csv');
echo $csv;
exit;
