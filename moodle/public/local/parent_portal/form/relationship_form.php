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

class relationship_form extends \moodleform {

    protected function definition() {
        $mform = $this->_form;
        $search = $this->_customdata['search'] ?? '';
        $mform->addElement('text', 'search', get_string('search'), ['value' => $search]);
        $mform->setType('search', PARAM_RAW);
        $this->add_action_buttons(false, get_string('search'));
    }
}
