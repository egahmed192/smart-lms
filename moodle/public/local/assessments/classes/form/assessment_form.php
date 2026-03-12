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

require_once($GLOBALS['CFG']->libdir . '/formslib.php');

namespace local_assessments\form;

class assessment_form extends \moodleform {

    protected function definition() {
        $mform = $this->_form;
        $assessment = $this->_customdata['assessment'] ?? null;
        $courseid = $this->_customdata['courseid'];
        $cansecretive = $this->_customdata['can_secretive'] ?? false;

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);
        if ($assessment) {
            $mform->addElement('hidden', 'id', $assessment->id);
            $mform->setType('id', PARAM_INT);
        }

        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required');

        $mform->addElement('text', 'publicid', get_string('publicid', 'local_assessments'));
        $mform->setType('publicid', PARAM_ALPHANUMEXT);

        $options = ['public' => get_string('type_public', 'local_assessments')];
        if ($cansecretive) {
            $options['secretive'] = get_string('type_secretive', 'local_assessments');
        }
        $mform->addElement('select', 'type', get_string('type', 'local_assessments'), $options);
        $mform->setType('type', PARAM_ALPHA);
        if (!$cansecretive) {
            $mform->hardFreeze('type');
        }

        $mform->addElement('float', 'fullmark', get_string('fullmark', 'local_assessments'));
        $mform->setType('fullmark', PARAM_FLOAT);
        $mform->addRule('fullmark', null, 'required');
        $mform->setDefault('fullmark', 100);

        $mform->addElement('float', 'weight', get_string('weight', 'local_assessments'));
        $mform->setType('weight', PARAM_FLOAT);
        $mform->addRule('weight', null, 'required');
        $mform->setDefault('weight', 100);

        $mform->addElement('editor', 'description', get_string('description'));
        $mform->setType('description', PARAM_RAW);

        $mform->addElement('date_time_selector', 'announcementdate', get_string('announcementdate', 'local_assessments'), ['optional' => true]);
        $mform->addElement('date_time_selector', 'assessmentdate', get_string('assessmentdate', 'local_assessments'), ['optional' => true]);

        $this->add_action_buttons(true);
    }
}
