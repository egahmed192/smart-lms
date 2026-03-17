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
require_once(__DIR__ . '/bulk_lib.php');

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
// All non-site courses for filtering (id => fullname).
$courses = $DB->get_records_sql_menu(
    "SELECT id, fullname
       FROM {course}
      WHERE id <> ?
   ORDER BY fullname ASC",
    [SITEID]
);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('bulk_message', 'local_message_audit'));

echo '<form method="get" action="bulk.php" class="mb-3" id="bulk-filter-form">';
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
    'parents_of_class' => get_string('target_parents_of_class', 'local_message_audit'),
];
foreach ($targets as $t => $label) {
    echo '<option value="' . s($t) . '"' . ($target === $t ? ' selected' : '') . '>' . $label . '</option>';
}
echo '</select></div>';

echo '<div class="mb-2" id="bulk-course-row"><label class="me-2">' . get_string('course') . ' (optional)</label>';
echo '<select name="courseid" id="bulk-courseid" class="form-select d-inline-block w-auto">';
echo '<option value="0">' . get_string('all') . '</option>';
foreach ($courses as $cid => $cname) {
    $selected = ((int)$courseid === (int)$cid) ? ' selected' : '';
    echo '<option value="' . (int)$cid . '"' . $selected . '>' . format_string($cname) . '</option>';
}
echo '</select></div>';

echo '<div class="mb-2" id="bulk-class-row" style="display:none;"><label class="me-2">' . get_string('class', 'local_message_audit') . '</label>';
echo '<select name="classkey" id="bulk-classkey" class="form-select d-inline-block w-auto">';
echo '<option value="">-- ' . get_string('choose') . ' --</option>';
foreach ($classes as $key => $label) {
    echo '<option value="' . s($key) . '"' . ($classkey === $key ? ' selected' : '') . '>' . s($label) . '</option>';
}
echo '</select></div>';

echo '<button type="submit" class="btn btn-secondary" id="bulk-update-count">' . get_string('update_count', 'local_message_audit') . '</button>';
echo '</form>';

echo '<p class="mb-2"><strong>' . get_string('recipients', 'local_message_audit') . ': <span id="bulk-recipient-count">' . $count . '</span></strong></p>';
if ($count === 0) {
    echo '<p class="text-muted">' . get_string('no_recipients', 'local_message_audit') . '</p>';
}

$form2 = '<form method="post" action="bulk.php">';
$form2 .= '<input type="hidden" name="target" id="bulk-post-target" value="' . s($target) . '">';
$form2 .= '<input type="hidden" name="courseid" id="bulk-post-courseid" value="' . (int)$courseid . '">';
$form2 .= '<input type="hidden" name="classkey" id="bulk-post-classkey" value="' . s($classkey) . '">';
$form2 .= '<input type="hidden" name="cohortid" value="0">';
$form2 .= '<input type="hidden" name="confirm" value="1">';
$form2 .= '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
$form2 .= '<label class="form-label">' . get_string('message', 'local_message_audit') . '</label><br><textarea name="message" class="form-control" rows="4" cols="60" required></textarea><br>';
$form2 .= '<button type="submit" id="bulk-send-btn" class="btn btn-primary mt-2" ' . ($count === 0 ? ' disabled' : '') . '>' . get_string('send_to_n_recipients', 'local_message_audit', $count) . '</button></form>';
echo $form2;

$sendToLabelTemplate = get_string('send_to_n_recipients', 'local_message_audit', '%s');
$PAGE->requires->js_amd_inline("
require(['jquery'], function($) {
    var countUrl = " . json_encode((new moodle_url('/local/message_audit/bulk_count.php'))->out(false)) . ";
    var sendToLabelTemplate = " . json_encode($sendToLabelTemplate) . ";
    var updateTimer = null;
    function syncPostHidden() {
        $('#bulk-post-target').val($('#bulk-target').val());
        $('#bulk-post-courseid').val($('#bulk-courseid').val() || 0);
        $('#bulk-post-classkey').val($('#bulk-classkey').val() || '');
    }
    function updateCount() {
        var data = {
            target: $('#bulk-target').val(),
            courseid: $('#bulk-courseid').val() || 0,
            classkey: $('#bulk-classkey').val() || ''
        };
        $.ajax({
            url: countUrl,
            type: 'GET',
            data: data,
            dataType: 'json'
        }).done(function(resp) {
            var c = parseInt(resp.count, 10) || 0;
            $('#bulk-recipient-count').text(c);
            $('#bulk-send-btn').prop('disabled', c === 0).text(sendToLabelTemplate.replace('%s', c));
        });
    }
    function scheduleUpdate() {
        syncPostHidden();
        if (updateTimer) {
            clearTimeout(updateTimer);
        }
        updateTimer = setTimeout(updateCount, 200);
    }
    function bulkToggle() {
        var t = $('#bulk-target').val();
        $('#bulk-course-row').toggle(t === 'students' || t === 'teachers' || t === 'parents' || t === 'all');
        $('#bulk-class-row').toggle(t === 'class_students' || t === 'class_teachers' || t === 'class_all' || t === 'parents_of_class');
    }
    $('#bulk-filter-form').on('submit', function(e) {
        e.preventDefault();
        scheduleUpdate();
    });
    $('#bulk-target').on('change', function() {
        bulkToggle();
        scheduleUpdate();
    });
    $('#bulk-courseid').on('input change', scheduleUpdate);
    $('#bulk-classkey').on('change', scheduleUpdate);
    bulkToggle();
    syncPostHidden();
});
");

echo $OUTPUT->footer();

// Recipient selection helpers are now in bulk_lib.php (shared with bulk_count.php).
