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

namespace local_studentlife\form;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

final class task_form extends \moodleform {
    protected function definition(): void {
        $mform = $this->_form;
        $task = $this->_customdata['task'] ?? null;
        $courseid = $this->_customdata['courseid'] ?? null;
        $courseoptions = $this->_customdata['courseoptions'] ?? [0 => get_string('none')];

        if ($task) {
            $mform->addElement('hidden', 'id', $task->id);
            $mform->setType('id', PARAM_INT);
        }

        if ($courseid) {
            $mform->addElement('hidden', 'courseid', $courseid);
            $mform->setType('courseid', PARAM_INT);
        } else {
            $mform->addElement('select', 'courseid', get_string('planner:course', 'local_studentlife'), $courseoptions);
            $mform->setType('courseid', PARAM_INT);
            $mform->setDefault('courseid', 0);
        }

        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required');

        $mform->addElement('textarea', 'description', get_string('description'), ['rows' => 4]);
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('text', 'estimatedminutes', get_string('planner:estimatedminutes', 'local_studentlife'));
        $mform->setType('estimatedminutes', PARAM_INT);

        $mform->addElement('date_time_selector', 'duetime', get_string('planner:duetime', 'local_studentlife'), ['optional' => true]);

        $this->add_action_buttons(true);
    }
}

