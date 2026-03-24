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

class block_course_onboarding_edit_form extends block_edit_form {
    protected function specific_definition($mform): void {
        global $COURSE;

        $mform->addElement('header', 'configheader', get_string('pluginname', 'block_course_onboarding'));

        $options = [0 => get_string('none')];
        if (!empty($COURSE) && !empty($COURSE->id) && $COURSE->id != SITEID) {
            $modinfo = get_fast_modinfo($COURSE);
            foreach ($modinfo->get_cms() as $cm) {
                if ($cm->uservisible) {
                    $options[$cm->id] = $cm->get_formatted_name();
                }
            }
        }

        $mform->addElement('select', 'config_nextcmid', get_string('settings:nextcm', 'block_course_onboarding'), $options);
        $mform->setType('config_nextcmid', PARAM_INT);

        $mform->addElement('editor', 'config_rules', get_string('settings:rules', 'block_course_onboarding'));
        $mform->setType('config_rules', PARAM_RAW);
    }
}

