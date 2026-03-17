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
require_once(__DIR__ . '/form/log_filter_form.php');

admin_externalpage_setup('local_message_audit');
require_capability('local/message_audit:view_logs', context_system::instance());

$PAGE->set_url(new moodle_url('/local/message_audit/index.php'));
$PAGE->set_title(get_string('message_log', 'local_message_audit'));
$PAGE->set_heading(get_string('message_log', 'local_message_audit'));

$page = optional_param('page', 0, PARAM_INT);
$perpage = 50;

// Build select options for sender/receiver/user/course based on existing log data.
function local_message_audit_get_user_options(string $field): array {
    global $DB;
    $sql = "SELECT DISTINCT l.{$field} AS uid, u.firstname, u.lastname
              FROM {local_message_audit_log} l
              JOIN {user} u ON u.id = l.{$field}
             WHERE u.deleted = 0
          ORDER BY u.lastname, u.firstname, u.id";
    $records = $DB->get_records_sql($sql);
    $options = [];
    foreach ($records as $r) {
        if (empty($r->uid)) {
            continue;
        }
        $options[$r->uid] = fullname((object)['firstname' => $r->firstname, 'lastname' => $r->lastname]);
    }
    return $options;
}

function local_message_audit_get_course_options(): array {
    global $DB;
    $sql = "SELECT DISTINCT l.courseid AS cid, c.fullname
              FROM {local_message_audit_log} l
              JOIN {course} c ON c.id = l.courseid
          ORDER BY c.fullname, c.id";
    $records = $DB->get_records_sql($sql);
    $options = [];
    foreach ($records as $r) {
        if (empty($r->cid)) {
            continue;
        }
        $options[$r->cid] = $r->fullname;
    }
    return $options;
}

$selectoptions = [
    'senders' => local_message_audit_get_user_options('senderid'),
    'receivers' => local_message_audit_get_user_options('receiverid'),
    'users' => local_message_audit_get_user_options('senderid'), // Any user (sender/receiver) – approximate from senders.
    'courses' => local_message_audit_get_course_options(),
];

$filterurl = new moodle_url('/local/message_audit/index.php');
$mform = new local_message_audit_log_filter_form($filterurl->out(false), $selectoptions, 'get');

$sort = optional_param('sort', 'time', PARAM_ALPHANUMEXT);
$dir = optional_param('dir', 'DESC', PARAM_ALPHA);
$dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';

$filters = (object)[
    'senderid' => optional_param('senderid', 0, PARAM_INT),
    'receiverid' => optional_param('receiverid', 0, PARAM_INT),
    'userid' => optional_param('userid', 0, PARAM_INT),
    'courseid' => optional_param('courseid', 0, PARAM_INT),
    'flagged' => optional_param('flagged', -1, PARAM_INT),
    'bulk_send' => optional_param('bulk_send', -1, PARAM_INT),
    'message' => optional_param('message', '', PARAM_TEXT),
    'usedatefrom' => optional_param('usedatefrom', 0, PARAM_BOOL),
    'usedateto' => optional_param('usedateto', 0, PARAM_BOOL),
    'datefrom' => optional_param('datefrom', 0, PARAM_INT),
    'dateto' => optional_param('dateto', 0, PARAM_INT),
    'sort' => $sort,
    'dir' => $dir,
    'page' => $page,
];

$mform->set_data($filters);

$sql = "SELECT l.id, l.timecreated, l.senderid, l.receiverid, l.courseid, l.message_text, l.flagged, l.reason, l.bulk_send,
               su.firstname AS sfirst, su.lastname AS slast, ru.firstname AS rfirst, ru.lastname AS rlast,
               c.fullname AS coursename
          FROM {local_message_audit_log} l
          LEFT JOIN {user} su ON su.id = l.senderid
          LEFT JOIN {user} ru ON ru.id = l.receiverid
          LEFT JOIN {course} c ON c.id = l.courseid
         WHERE 1=1";
$params = [];

if ($filters->userid > 0) {
    $sql .= " AND (l.senderid = :uid OR l.receiverid = :uid2)";
    $params['uid'] = $filters->userid;
    $params['uid2'] = $filters->userid;
}
if ($filters->senderid > 0) {
    $sql .= " AND l.senderid = :senderid";
    $params['senderid'] = $filters->senderid;
}
if ($filters->receiverid > 0) {
    $sql .= " AND l.receiverid = :receiverid";
    $params['receiverid'] = $filters->receiverid;
}
if ($filters->courseid > 0) {
    $sql .= " AND l.courseid = :courseid";
    $params['courseid'] = $filters->courseid;
}
if ($filters->flagged >= 0) {
    $sql .= " AND l.flagged = :flagged";
    $params['flagged'] = $filters->flagged;
}
if ($filters->bulk_send >= 0) {
    $sql .= " AND l.bulk_send = :bulksend";
    $params['bulksend'] = $filters->bulk_send;
}
if (trim($filters->message) !== '') {
    $sql .= " AND " . $DB->sql_like('l.message_text', ':message', false, false);
    $params['message'] = '%' . $DB->sql_like_escape(trim($filters->message)) . '%';
}
if (!empty($filters->usedatefrom) && !empty($filters->datefrom)) {
    $sql .= " AND l.timecreated >= :datefrom";
    $params['datefrom'] = $filters->datefrom;
}
if (!empty($filters->usedateto) && !empty($filters->dateto)) {
    // Include the entire day (up to 23:59:59) for the selected date.
    $sql .= " AND l.timecreated <= :dateto";
    $params['dateto'] = $filters->dateto + DAYSECS - 1;
}

// Sorting.
$sortmap = [
    'time' => "l.timecreated {$dir}",
    'sender' => "su.lastname {$dir}, su.firstname {$dir}, l.senderid {$dir}",
    'receiver' => "ru.lastname {$dir}, ru.firstname {$dir}, l.receiverid {$dir}",
    'course' => "c.fullname {$dir}, l.courseid {$dir}",
    'flagged' => "l.flagged {$dir}, l.timecreated DESC",
    'bulk' => "l.bulk_send {$dir}, l.timecreated DESC",
];
$orderby = $sortmap[$sort] ?? $sortmap['time'];
$sql .= " ORDER BY {$orderby}";

$total = $DB->count_records_sql("SELECT COUNT(*) FROM ({$sql}) c", $params);
$logs = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('message_log', 'local_message_audit'));

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/message_audit/index.php'));
}

echo $mform->render();
echo html_writer::div(
    html_writer::link(new moodle_url('/local/message_audit/keywords.php'), get_string('keyword_rules', 'local_message_audit')),
    'mb-3'
);

$table = new html_table();

// Build sortable header links while keeping current filters.
$baseparams = [
    'senderid' => $filters->senderid,
    'receiverid' => $filters->receiverid,
    'userid' => $filters->userid,
    'courseid' => $filters->courseid,
    'flagged' => $filters->flagged,
    'bulk_send' => $filters->bulk_send,
    'message' => $filters->message,
    'usedatefrom' => $filters->usedatefrom,
    'usedateto' => $filters->usedateto,
    'datefrom' => $filters->datefrom,
    'dateto' => $filters->dateto,
];
// Reset page when changing sort.
$sortlink = function(string $label, string $field) use ($baseparams, $sort, $dir): string {
    $nextdir = 'ASC';
    if ($sort === $field && $dir === 'ASC') {
        $nextdir = 'DESC';
    }
    $u = new moodle_url('/local/message_audit/index.php', $baseparams + ['sort' => $field, 'dir' => $nextdir, 'page' => 0]);
    return html_writer::link($u, $label);
};

$table->head = [
    $sortlink(get_string('time', 'local_message_audit'), 'time'),
    $sortlink(get_string('sender', 'local_message_audit'), 'sender'),
    $sortlink(get_string('receiver', 'local_message_audit'), 'receiver'),
    $sortlink(get_string('course'), 'course'),
    get_string('message', 'local_message_audit'),
    $sortlink(get_string('flagged', 'local_message_audit'), 'flagged'),
    $sortlink(get_string('bulk_message', 'local_message_audit'), 'bulk'),
];
$table->data = [];
foreach ($logs as $log) {
    $sender = $log->sfirst !== null ? fullname((object)['firstname' => $log->sfirst, 'lastname' => $log->slast]) : $log->senderid;
    $receiver = $log->rfirst !== null ? fullname((object)['firstname' => $log->rfirst, 'lastname' => $log->rlast]) : $log->receiverid;
    $preview = $log->message_text ? (mb_substr($log->message_text, 0, 80) . '...') : '-';
    $course = $log->courseid ? ($log->coursename ?: $log->courseid) : '-';
    $table->data[] = [
        userdate($log->timecreated),
        $sender,
        $receiver,
        $course,
        $preview,
        $log->flagged ? ('Yes: ' . $log->reason) : 'No',
        $log->bulk_send ? get_string('yes') : get_string('no'),
    ];
}
if (!empty($table->data)) {
    echo html_writer::table($table);
    $pagingparams = [
        'senderid' => $filters->senderid,
        'receiverid' => $filters->receiverid,
        'userid' => $filters->userid,
    'courseid' => $filters->courseid,
        'flagged' => $filters->flagged,
        'bulk_send' => $filters->bulk_send,
        'message' => $filters->message,
        'usedatefrom' => $filters->usedatefrom,
        'usedateto' => $filters->usedateto,
        'datefrom' => $filters->datefrom,
        'dateto' => $filters->dateto,
        'sort' => $sort,
        'dir' => $dir,
    ];
    echo $OUTPUT->paging_bar($total, $page, $perpage, new moodle_url('/local/message_audit/index.php', $pagingparams));
} else {
    echo $OUTPUT->notification(get_string('no_messages', 'local_message_audit'), 'info');
}

echo $OUTPUT->footer();
