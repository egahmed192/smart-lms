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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Filter form for the message audit log page.
 */
class local_message_audit_log_filter_form extends moodleform {
    public function definition(): void {
        global $OUTPUT;
        $mform = $this->_form;

        // Use core strings to avoid missing lang cache issues.
        $mform->addElement('header', 'filters', get_string('filters'));

        // Single-field selects with names (no IDs in UI).
        $senders = $this->_customdata['senders'] ?? [];
        $receivers = $this->_customdata['receivers'] ?? [];
        $users = $this->_customdata['users'] ?? [];
        $courses = $this->_customdata['courses'] ?? [];
        $anyoption = [0 => get_string('all')];

        $mform->addElement('select', 'senderid', get_string('sender', 'local_message_audit'), $anyoption + $senders);
        $mform->setType('senderid', PARAM_INT);
        $mform->addElement('select', 'receiverid', get_string('receiver', 'local_message_audit'), $anyoption + $receivers);
        $mform->setType('receiverid', PARAM_INT);
        $mform->addElement('select', 'userid', get_string('user'), $anyoption + $users);
        $mform->setType('userid', PARAM_INT);
        $mform->addElement('select', 'courseid', get_string('course'), $anyoption + $courses);
        $mform->setType('courseid', PARAM_INT);

        $tri = [
            -1 => get_string('all'),
            1 => get_string('yes'),
            0 => get_string('no'),
        ];
        $mform->addElement('select', 'flagged', get_string('flagged', 'local_message_audit'), $tri);
        $mform->setType('flagged', PARAM_INT);

        $mform->addElement('select', 'bulk_send', get_string('bulk_message', 'local_message_audit'), $tri);
        $mform->setType('bulk_send', PARAM_INT);

        $mform->addElement('text', 'reason', 'Reason', ['size' => 40]);
        $mform->setType('reason', PARAM_TEXT);

        $mform->addElement('text', 'message', get_string('message', 'local_message_audit'), ['size' => 40]);
        $mform->setType('message', PARAM_TEXT);

        // Optional date filters (avoid forcing a date UI when unused).
        $mform->addElement('advcheckbox', 'usedatefrom', get_string('fromdate'));
        $mform->setType('usedatefrom', PARAM_BOOL);
        $mform->addElement('date_selector', 'datefrom', get_string('fromdate'));
        $mform->setType('datefrom', PARAM_INT);
        $mform->disabledIf('datefrom', 'usedatefrom', 'notchecked');

        $mform->addElement('advcheckbox', 'usedateto', get_string('todate'));
        $mform->setType('usedateto', PARAM_BOOL);
        $mform->addElement('date_selector', 'dateto', get_string('todate'));
        $mform->setType('dateto', PARAM_INT);
        $mform->disabledIf('dateto', 'usedateto', 'notchecked');

        // Sorting.
        $sortopts = [
            'time' => get_string('time', 'local_message_audit'),
            'sender' => get_string('sender', 'local_message_audit'),
            'receiver' => get_string('receiver', 'local_message_audit'),
            'course' => get_string('course'),
            'flagged' => get_string('flagged', 'local_message_audit'),
            'bulk' => get_string('bulk_message', 'local_message_audit'),
        ];
        $diropts = [
            'DESC' => get_string('desc'),
            'ASC' => get_string('asc'),
        ];
        $mform->addElement('select', 'sort', get_string('sortby'), $sortopts);
        $mform->setType('sort', PARAM_ALPHANUMEXT);
        $mform->addElement('select', 'dir', get_string('sort'), $diropts);
        $mform->setType('dir', PARAM_ALPHA);

        $mform->addElement('hidden', 'page', 0);
        $mform->setType('page', PARAM_INT);

        $buttonarray = [];
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('filter'));
        $buttonarray[] = $mform->createElement('cancel', 'resetbutton', get_string('reset'));
        $mform->addGroup($buttonarray, 'buttonar', '', [' '], false);
    }
}

