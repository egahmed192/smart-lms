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

defined('MOODLE_INTERNAL') || die();

/**
 * Extend the main navigation with the Student life "Today" page link.
 *
 * @param global_navigation $nav
 */
function local_studentlife_extend_navigation(global_navigation $nav): void {
    if (!isloggedin() || isguestuser()) {
        return;
    }

    $todayurl = new moodle_url('/local/studentlife/today.php');
    $todaynode = navigation_node::create(
        get_string('today', 'local_studentlife'),
        $todayurl,
        navigation_node::TYPE_CUSTOM,
        null,
        'local_studentlife_today',
        new pix_icon('i/calendar', '')
    );

    $plannerurl = new moodle_url('/local/studentlife/planner.php');
    $plannernode = navigation_node::create(
        get_string('planner', 'local_studentlife'),
        $plannerurl,
        navigation_node::TYPE_CUSTOM,
        null,
        'local_studentlife_planner',
        new pix_icon('i/report', '')
    );

    $notifurl = new moodle_url('/local/studentlife/notifications.php');
    $notifnode = navigation_node::create(
        get_string('notifications', 'local_studentlife'),
        $notifurl,
        navigation_node::TYPE_CUSTOM,
        null,
        'local_studentlife_notifications',
        new pix_icon('i/notifications', '')
    );

    // Add to the "Home" branch when possible, otherwise fall back to root.
    if ($nav->find('home', navigation_node::TYPE_ROOTNODE)) {
        $nav->add_node($todaynode, 'home');
        $nav->add_node($plannernode, 'home');
        $nav->add_node($notifnode, 'home');
    } else {
        $nav->add_node($todaynode);
        $nav->add_node($plannernode);
        $nav->add_node($notifnode);
    }
}

/**
 * File serving for local_studentlife.
 */
function local_studentlife_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []): bool {
    if ($filearea !== \local_studentlife\local\offline_pack_service::FILEAREA_OFFLINEPACK) {
        return false;
    }

    if ($context->contextlevel !== CONTEXT_USER) {
        return false;
    }

    require_login();
    if ((int)$context->instanceid !== (int)$GLOBALS['USER']->id) {
        return false;
    }

    $itemid = (int)array_shift($args); // courseid.
    $filename = array_pop($args);
    $filepath = '/' . (count($args) ? implode('/', $args) . '/' : '');

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_studentlife', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }

    send_stored_file($file, 0, 0, true, $options);
    return true;
}
