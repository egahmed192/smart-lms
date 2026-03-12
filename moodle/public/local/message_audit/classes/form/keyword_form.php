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

namespace local_message_audit\form;

class keyword_form extends \moodleform {

    protected function definition() {
        $mform = $this->_form;
        $keyword = $this->_customdata['keyword'] ?? null;
        if ($keyword) {
            $mform->addElement('hidden', 'id', $keyword->id);
            $mform->setType('id', PARAM_INT);
        }
        $mform->addElement('text', 'pattern', get_string('pattern', 'local_message_audit'), ['size' => 60]);
        $mform->setType('pattern', PARAM_RAW);
        $mform->addRule('pattern', null, 'required');
        $mform->addElement('select', 'severity', get_string('severity', 'local_message_audit'), ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']);
        $mform->addElement('select', 'action', get_string('action', 'local_message_audit'), [
            'flag' => 'Flag',
            'notify_admin' => 'Notify admin',
        ]);
        $this->add_action_buttons(true);
    }
}
