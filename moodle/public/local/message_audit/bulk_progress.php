<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.moodle.org/licenses/>.

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_message_audit_bulk');
require_capability('local/message_audit:send_bulk_message', context_system::instance());

$PAGE->set_url(new moodle_url('/local/message_audit/bulk_progress.php'));
$PAGE->set_title('Sending bulk messages');
$PAGE->set_heading('Sending bulk messages');

if (!isset($_SESSION['local_message_audit_bulk_recipients']) || !is_array($_SESSION['local_message_audit_bulk_recipients'])) {
    redirect(new moodle_url('/local/message_audit/bulk.php'), 'No bulk send in progress.', null, \core\output\notification::NOTIFY_WARNING);
}

$total = count($_SESSION['local_message_audit_bulk_recipients']);
$chunkurl = new moodle_url('/local/message_audit/bulk_send_chunk.php');
$chunkurlabsolute = $chunkurl->out(true);
$backurl = new moodle_url('/local/message_audit/bulk.php');
$sesskey = sesskey();

echo $OUTPUT->header();
echo $OUTPUT->heading('Sending bulk messages');
echo html_writer::tag('p', 'Sending to ' . $total . ' recipients. Please wait…', ['class' => 'mb-3']);
echo html_writer::div(html_writer::div('', 'progress-bar progress-bar-striped progress-bar-animated', ['role' => 'progressbar', 'style' => 'width: 0%']), 'progress mb-3', ['id' => 'bulk-progress-bar', 'style' => 'height: 24px;', 'aria-valuenow' => '0', 'aria-valuemin' => '0', 'aria-valuemax' => (string)$total]);
echo html_writer::tag('p', 'Sent 0 of ' . $total, ['id' => 'bulk-progress-status', 'class' => 'mb-3']);
echo html_writer::div('', '', ['id' => 'bulk-progress-done']);

$PAGE->requires->js_amd_inline("
require(['jquery'], function($) {
    var total = " . (int)$total . ";
    var chunkUrl = " . json_encode($chunkurlabsolute) . ";
    var backUrl = " . json_encode($backurl->out(false)) . ";
    var sesskey = " . json_encode($sesskey) . ";

    function runChunk() {
        $.ajax({
            url: chunkUrl,
            type: 'POST',
            data: { sesskey: sesskey },
            dataType: 'json'
        }).done(function(data) {
            if (typeof data !== 'object' || data === null) {
                $('#bulk-progress-done').html('<div class=\"alert alert-danger\">Invalid response.</div><a href=\"' + backUrl + '\" class=\"btn btn-secondary\">Back to bulk message</a>');
                return;
            }
            if (data.error === 'auth') {
                $('#bulk-progress-done').html('<div class=\"alert alert-warning\">Session expired or invalid. Please <a href=\"' + backUrl + '\">go back</a> and start the bulk send again.</div>');
                return;
            }
            if (data.error === 'nodata') {
                $('#bulk-progress-done').html('<div class=\"alert alert-warning\">No recipients in session. Please <a href=\"' + backUrl + '\">go back</a> and start the bulk send again.</div>');
                return;
            }
            var sent = parseInt(data.sent, 10) || 0;
            var tot = parseInt(data.total, 10) || total;
            var failed = parseInt(data.failed, 10) || 0;
            var pct = tot > 0 ? Math.min(100, Math.round((sent / tot) * 100)) : 0;
            $('#bulk-progress-bar').attr('aria-valuenow', sent);
            $('#bulk-progress-bar .progress-bar').css('width', pct + '%');
            var statusText = 'Sent ' + sent + ' of ' + tot;
            if (failed > 0) {
                statusText += ' (failed ' + failed + ')';
            }
            $('#bulk-progress-status').text(statusText);

            if (data.done) {
                $('#bulk-progress-bar .progress-bar').css('width', '100%').removeClass('progress-bar-animated');
                var msg = 'Bulk message sent to ' + sent + ' recipients.';
                $('#bulk-progress-done').html('<div class=\"alert alert-success\">' + msg + '</div><a href=\"' + backUrl + '\" class=\"btn btn-primary\">Back to bulk message</a>');
                return;
            }
            setTimeout(runChunk, 150);
        }).fail(function(xhr, status, err) {
            var errMsg = 'An error occurred while sending.';
            if (xhr.responseText && xhr.responseText.length < 200) {
                errMsg = errMsg + ' (' + xhr.responseText.substring(0, 100) + ')';
            }
            $('#bulk-progress-done').html('<div class=\"alert alert-danger\">' + errMsg + '</div><a href=\"' + backUrl + '\" class=\"btn btn-secondary\">Back to bulk message</a>');
        });
    }
    runChunk();
});
");

echo $OUTPUT->footer();
