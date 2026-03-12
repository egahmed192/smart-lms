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

admin_externalpage_setup('local_message_audit');
require_capability('local/message_audit:view_logs', context_system::instance());

$PAGE->set_url(new moodle_url('/local/message_audit/index.php'));
$PAGE->set_title(get_string('message_log', 'local_message_audit'));
$PAGE->set_heading(get_string('message_log', 'local_message_audit'));

$userid = optional_param('userid', 0, PARAM_INT);
$flagged = optional_param('flagged', -1, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$perpage = 50;

$sql = "SELECT l.id, l.timecreated, l.senderid, l.receiverid, l.courseid, l.message_text, l.flagged, l.reason,
               su.firstname AS sfirst, su.lastname AS slast, ru.firstname AS rfirst, ru.lastname AS rlast
          FROM {local_message_audit_log} l
          LEFT JOIN {user} su ON su.id = l.senderid
          LEFT JOIN {user} ru ON ru.id = l.receiverid
         WHERE 1=1";
$params = [];
if ($userid > 0) {
    $sql .= " AND (l.senderid = :uid OR l.receiverid = :uid2)";
    $params['uid'] = $userid;
    $params['uid2'] = $userid;
}
if ($flagged >= 0) {
    $sql .= " AND l.flagged = :flagged";
    $params['flagged'] = $flagged;
}
$sql .= " ORDER BY l.timecreated DESC";

$total = $DB->count_records_sql("SELECT COUNT(*) FROM ({$sql}) c", $params);
$logs = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('message_log', 'local_message_audit'));

global $DB;
$filterurl = new moodle_url('/local/message_audit/index.php');
echo html_writer::link(new moodle_url($filterurl, ['flagged' => 1]), get_string('flagged', 'local_message_audit') . ' only') . ' | ';
echo html_writer::link($filterurl, 'All') . ' | ';
echo html_writer::link(new moodle_url('/local/message_audit/keywords.php'), get_string('keyword_rules', 'local_message_audit')) . '<br><br>';

$table = new html_table();
$table->head = [get_string('time', 'local_message_audit'), get_string('sender', 'local_message_audit'), get_string('receiver', 'local_message_audit'), 'Course', 'Preview', get_string('flagged', 'local_message_audit')];
$table->data = [];
foreach ($logs as $log) {
    $sender = $log->sfirst !== null ? fullname((object)['firstname' => $log->sfirst, 'lastname' => $log->slast]) : $log->senderid;
    $receiver = $log->rfirst !== null ? fullname((object)['firstname' => $log->rfirst, 'lastname' => $log->rlast]) : $log->receiverid;
    $preview = $log->message_text ? (mb_substr($log->message_text, 0, 80) . '...') : '-';
    $table->data[] = [
        userdate($log->timecreated),
        $sender,
        $receiver,
        $log->courseid ? $log->courseid : '-',
        $preview,
        $log->flagged ? ('Yes: ' . $log->reason) : 'No',
    ];
}
if (!empty($table->data)) {
    echo html_writer::table($table);
    echo $OUTPUT->paging_bar($total, $page, $perpage, new moodle_url('/local/message_audit/index.php', ['userid' => $userid, 'flagged' => $flagged]));
} else {
    echo $OUTPUT->notification(get_string('no_messages', 'local_message_audit'), 'info');
}

echo $OUTPUT->footer();
