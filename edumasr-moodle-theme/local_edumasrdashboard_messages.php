<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Edumasr custom messages page - uses theme dashboard layout and core messaging widget.
 *
 * Copy this file to: public/local/edumasrdashboard/messages.php
 *
 * @package    local_edumasrdashboard
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

require_login(null, false);

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if (empty($CFG->messaging)) {
    throw new \moodle_exception('disabled', 'message');
}

$id = optional_param('id', 0, PARAM_INT);
$view = optional_param('view', null, PARAM_ALPHANUM);
$userid = optional_param('user2', $id, PARAM_INT);
$conversationid = optional_param('convid', null, PARAM_INT);

if (!\core_user::is_real_user($userid)) {
    $userid = null;
}
if ($userid) {
    $conversationid = \core_message\api::get_conversation_between_users([$USER->id, $userid]);
} else if ($conversationid) {
    if (!\core_message\api::is_user_in_conversation($USER->id, $conversationid)) {
        $conversationid = null;
    }
}

if ($userid && !\core_message\api::can_send_message($userid, $USER->id)) {
    throw new moodle_exception('Cannot contact user');
}

$url = new moodle_url('/local/edumasrdashboard/messages.php');
if ($userid) {
    $url->param('id', $userid);
}
if ($conversationid) {
    $url->param('convid', $conversationid);
}
if ($view) {
    $url->param('view', $view);
}

$PAGE->set_url($url);
$PAGE->set_context(\context_user::instance($USER->id));
$PAGE->set_pagelayout('messages');

$title = get_string('messages', 'message');
$PAGE->set_title($title);
$PAGE->set_heading($title);

$PAGE->set_secondary_navigation(false);

echo $OUTPUT->header();

if (!get_user_preferences('core_message_migrate_data', false)) {
    $notify = new \core\output\notification(
        get_string('messagingdatahasnotbeenmigrated', 'message'),
        \core\output\notification::NOTIFY_WARNING
    );
    echo $OUTPUT->render($notify);
}

echo \core_message\helper::render_messaging_widget(false, $userid, $conversationid, $view);

echo $OUTPUT->footer();
