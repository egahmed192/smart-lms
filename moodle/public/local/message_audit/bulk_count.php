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

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once(__DIR__ . '/bulk_lib.php');

require_login(null, false);
require_capability('local/message_audit:send_bulk_message', context_system::instance());

header('Content-Type: application/json; charset=utf-8');

$target = required_param('target', PARAM_ALPHAEXT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$classkey = optional_param('classkey', '', PARAM_ALPHANUMEXT);
$cohortid = optional_param('cohortid', 0, PARAM_INT);

$recipients = local_message_audit_bulk_get_recipients($DB, $target, $courseid, $classkey, $cohortid);
$recipients = array_values(array_unique(array_filter(array_map('intval', $recipients))));

echo json_encode([
    'count' => count($recipients),
]);

