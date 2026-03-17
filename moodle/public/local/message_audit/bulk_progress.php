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
$PAGE->set_title(get_string('bulk_sending', 'local_message_audit'));
$PAGE->set_heading(get_string('bulk_sending', 'local_message_audit'));

if (!isset($_SESSION['local_message_audit_bulk_recipients']) || !is_array($_SESSION['local_message_audit_bulk_recipients'])) {
    redirect(new moodle_url('/local/message_audit/bulk.php'), get_string('bulk_progress_nodata', 'local_message_audit'), null, \core\output\notification::NOTIFY_WARNING);
}

$total = count($_SESSION['local_message_audit_bulk_recipients']);
$chunkurl = new moodle_url('/local/message_audit/bulk_send_chunk.php');
$chunkurlabsolute = $chunkurl->out(true);
$backurl = new moodle_url('/local/message_audit/bulk.php');
$sesskey = sesskey();

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('bulk_sending', 'local_message_audit'));
echo html_writer::tag('p', get_string('bulk_progress_intro', 'local_message_audit', $total), ['class' => 'mb-3']);
echo html_writer::div(html_writer::div('', 'progress-bar progress-bar-striped progress-bar-animated', ['role' => 'progressbar', 'style' => 'width: 0%']), 'progress mb-3', ['id' => 'bulk-progress-bar', 'style' => 'height: 24px;', 'aria-valuenow' => '0', 'aria-valuemin' => '0', 'aria-valuemax' => (string)$total]);
echo html_writer::tag('p', get_string('bulk_progress_status', 'local_message_audit', (object)['sent' => 0, 'total' => $total]), ['id' => 'bulk-progress-status', 'class' => 'mb-3']);
echo html_writer::div('', '', ['id' => 'bulk-progress-done']);

$PAGE->requires->js_amd_inline("
require(['jquery', 'core/str'], function($, Str) {
    var total = " . (int)$total . ";
    var chunkUrl = " . json_encode($chunkurlabsolute) . ";
    var backUrl = " . json_encode($backurl->out(false)) . ";
    var sesskey = " . json_encode($sesskey) . ";
    var backLabel = " . json_encode(get_string('back_to_bulk', 'local_message_audit')) . ";
    var invalidResp = " . json_encode(get_string('bulk_progress_error', 'local_message_audit')) . ";

    function setStatus(sent, tot, failed) {
        return Str.get_string('bulk_progress_status', 'local_message_audit', {sent: sent, total: tot}).then(function(s) {
            if (failed > 0) {
                s += ' (failed ' + failed + ')';
            }
            $('#bulk-progress-status').text(s);
        });
    }

    function runChunk() {
        $.ajax({
            url: chunkUrl,
            type: 'POST',
            data: { sesskey: sesskey },
            dataType: 'json'
        }).done(function(data) {
            if (typeof data !== 'object' || data === null) {
                $('#bulk-progress-done').html('<div class=\"alert alert-danger\">' + invalidResp + '</div><a href=\"' + backUrl + '\" class=\"btn btn-secondary\">' + backLabel + '</a>');
                return;
            }
            if (data.error === 'auth') {
                $('#bulk-progress-done').html('<div class=\"alert alert-warning\">' + invalidResp + '</div><a href=\"' + backUrl + '\" class=\"btn btn-secondary\">' + backLabel + '</a>');
                return;
            }
            if (data.error === 'nodata') {
                $('#bulk-progress-done').html('<div class=\"alert alert-warning\">' + invalidResp + '</div><a href=\"' + backUrl + '\" class=\"btn btn-secondary\">' + backLabel + '</a>');
                return;
            }
            var sent = parseInt(data.sent, 10) || 0;
            var tot = parseInt(data.total, 10) || total;
            var failed = parseInt(data.failed, 10) || 0;
            var pct = tot > 0 ? Math.min(100, Math.round((sent / tot) * 100)) : 0;
            $('#bulk-progress-bar').attr('aria-valuenow', sent);
            $('#bulk-progress-bar .progress-bar').css('width', pct + '%');
            setStatus(sent, tot, failed);

            if (data.done) {
                $('#bulk-progress-bar .progress-bar').css('width', '100%').removeClass('progress-bar-animated');
                Str.get_string('bulk_sent', 'local_message_audit', sent).then(function(msg) {
                    $('#bulk-progress-done').html('<div class=\"alert alert-success\">' + msg + '</div><a href=\"' + backUrl + '\" class=\"btn btn-primary\">' + backLabel + '</a>');
                });
                return;
            }
            setTimeout(runChunk, 150);
        }).fail(function(xhr, status, err) {
            var errMsg = 'An error occurred while sending.';
            if (xhr.responseText && xhr.responseText.length < 200) {
                errMsg = errMsg + ' (' + xhr.responseText.substring(0, 100) + ')';
            }
            $('#bulk-progress-done').html('<div class=\"alert alert-danger\">' + errMsg + '</div><a href=\"' + backUrl + '\" class=\"btn btn-secondary\">' + backLabel + '</a>');
        });
    }
    runChunk();
});
");

echo $OUTPUT->footer();
