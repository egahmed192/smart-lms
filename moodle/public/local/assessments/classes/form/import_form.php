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

class import_form extends \moodleform {

    protected function definition() {
        $mform = $this->_form;
        $mform->addElement('hidden', 'assessmentid', $this->_customdata['assessmentid']);
        $mform->setType('assessmentid', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('filepicker', 'csvfile', get_string('file'), null, ['accepted_types' => ['.csv', 'text/csv', 'text/plain']]);
        $mform->addRule('csvfile', null, 'required');
        $this->add_action_buttons(true, get_string('import'));
    }
}
