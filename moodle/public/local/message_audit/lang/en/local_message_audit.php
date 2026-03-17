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

$string['pluginname'] = 'Message audit';
$string['message_log'] = 'Message log';
$string['view_logs'] = 'View message logs';
$string['bulk_message'] = 'Send bulk message';
$string['sender'] = 'Sender';
$string['receiver'] = 'Receiver';
$string['time'] = 'Time';
$string['flagged'] = 'Flagged';
$string['keyword_rules'] = 'Keyword rules';
$string['add_keyword'] = 'Add keyword';
$string['messages_monitored_notice'] = 'All messages are monitored for compliance.';
$string['no_messages'] = 'No messages found.';
$string['no_keywords'] = 'No keyword rules.';
$string['pattern'] = 'Pattern';
$string['severity'] = 'Severity';
$string['action'] = 'Action';
$string['action_flag'] = 'Flag';
$string['action_notify_admin'] = 'Notify admin';
$string['action_flag_and_notify'] = 'Flag and notify';
$string['bulk_sent'] = 'Bulk message sent to {$a} recipients.';
$string['student_parent_violation'] = 'Student–parent messaging not allowed (sender lacks capability).';
$string['target'] = 'Target';
$string['target_students'] = 'All students';
$string['target_teachers'] = 'All teachers';
$string['target_parents'] = 'All parents';
$string['target_all'] = 'All users';
$string['target_class_students'] = 'All students in class';
$string['target_class_teachers'] = 'All teachers in class';
$string['target_class_all'] = 'All users in class';
$string['target_cohort'] = 'All users in cohort';
$string['target_parents_of_class'] = 'All parents of students in class';
$string['class'] = 'Class';
$string['cohort'] = 'Cohort';
$string['recipients'] = 'Recipients';
$string['no_recipients'] = 'No recipients match the selected filters. Choose different options.';
$string['message'] = 'Message';
$string['send_to_n_recipients'] = 'Send to {$a} recipients';
$string['class_label'] = 'Year {$a->year} – Class {$a->standard}';
$string['update_count'] = 'Update count';
$string['message_flagged_notify_subject'] = 'Message flagged for review';
$string['message_flagged_notify_body'] = 'A message was flagged by keyword rules.

Sender: {$a->sender}
Receiver: {$a->receiver}
Matched: {$a->reason}

Preview: {$a->preview}

View the message log for details.';
$string['bulk_send_notification'] = 'Bulk message sent to {$a} recipients.';
$string['bulk_sending'] = 'Sending bulk messages';
$string['bulk_progress_intro'] = 'Sending to {$a} recipients. Please wait…';
$string['bulk_progress_status'] = 'Sent {$a->sent} of {$a->total}';
$string['bulk_progress_nodata'] = 'No bulk send in progress.';
$string['bulk_progress_error'] = 'An error occurred while sending.';
$string['back_to_bulk'] = 'Back to bulk message';

// Log filters.
$string['filters_heading'] = 'Filters';
$string['filter_apply'] = 'Apply filters';
$string['filter_reset'] = 'Reset';
$string['senderid'] = 'Sender user ID';
$string['receiverid'] = 'Receiver user ID';
$string['anyuserid'] = 'Any user ID (sender or receiver)';
$string['courseid'] = 'Course ID';
$string['reason'] = 'Reason';
$string['message_contains'] = 'Message contains';
$string['date_from'] = 'From date';
$string['date_to'] = 'To date';
$string['use_date_from'] = 'Use from date';
$string['use_date_to'] = 'Use to date';
$string['sortby'] = 'Sort by';
$string['sortdir'] = 'Direction';
$string['sort_asc'] = 'Ascending';
$string['sort_desc'] = 'Descending';
$string['sort_time'] = 'Time';
$string['sort_sender'] = 'Sender';
$string['sort_receiver'] = 'Receiver';
$string['sort_course'] = 'Course';
$string['sort_flagged'] = 'Flagged';
$string['sort_bulk'] = 'Bulk message';
