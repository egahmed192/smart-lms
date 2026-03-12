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

class block_message_audit_notice extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_message_audit_notice');
    }

    public function get_content() {
        if (isset($this->content)) {
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->text = '<div class="alert alert-info">' . get_string('messages_monitored_notice', 'local_message_audit') . '</div>';
        $this->content->footer = '';
        return $this->content;
    }

    public function applicable_formats() {
        return ['all' => true];
    }
}
