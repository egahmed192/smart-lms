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

namespace local_parent_portal\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

class edit_relationship_form extends \moodleform {

    protected function definition() {
        global $DB;
        $mform = $this->_form;
        $mform->addElement('header', 'h', get_string('add_relationship', 'local_parent_portal'));

        $parents = $DB->get_records_sql_menu(
            "SELECT id, " . $DB->sql_fullname('firstname', 'lastname') . " FROM {user} WHERE deleted = 0 AND id IN (SELECT userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname = 'parent') ORDER BY lastname, firstname"
        );
        if (empty($parents)) {
            $parents = [0 => get_string('choosedots')];
        } else {
            $parents = [0 => get_string('choosedots')] + $parents;
        }
        $mform->addElement('select', 'parent_userid', get_string('parent', 'local_parent_portal'), $parents);
        $mform->setType('parent_userid', PARAM_INT);
        $mform->addRule('parent_userid', null, 'required');

        $students = $DB->get_records_sql_menu(
            "SELECT id, " . $DB->sql_fullname('firstname', 'lastname') . " FROM {user} WHERE deleted = 0 AND id IN (SELECT userid FROM {role_assignments} ra JOIN {role} r ON r.id = ra.roleid WHERE r.shortname = 'student') ORDER BY lastname, firstname"
        );
        if (empty($students)) {
            $students = [0 => get_string('choosedots')];
        } else {
            $students = [0 => get_string('choosedots')] + $students;
        }
        $mform->addElement('select', 'student_userid', get_string('student', 'local_parent_portal'), $students);
        $mform->setType('student_userid', PARAM_INT);
        $mform->addRule('student_userid', null, 'required');

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}
