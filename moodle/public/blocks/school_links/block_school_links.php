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

class block_school_links extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_school_links');
    }

    public function get_content() {
        global $CFG;
        if (isset($this->content)) {
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->footer = '';
        require_once($CFG->dirroot . '/theme/school/lib.php');
        $links = theme_school_get_role_links();
        if (empty($links)) {
            $this->content->text = '';
            return $this->content;
        }
        $items = [];
        foreach ($links as $link) {
            $items[] = \html_writer::link($link['url'], $link['text'], ['class' => 'list-group-item list-group-item-action']);
        }
        $this->content->text = \html_writer::tag('div', implode('', $items), ['class' => 'list-group']);
        return $this->content;
    }

    public function applicable_formats() {
        return ['all' => true];
    }
}
