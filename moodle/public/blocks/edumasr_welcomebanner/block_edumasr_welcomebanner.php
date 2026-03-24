<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Foundation, either version 3 of the License, or (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY.
// See the GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * EDUMASR Welcome Banner block.
 * Shows a welcome message with animated hand and View Schedule button on Dashboard.
 *
 * @package    block_edumasr_welcomebanner
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_edumasr_welcomebanner extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_edumasr_welcomebanner');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        try {
            $renderable = new block_edumasr_welcomebanner\output\main();
            $renderer = $this->page->get_renderer('block_edumasr_welcomebanner');
            $this->content->text = $renderer->render($renderable);
        } catch (Throwable $e) {
            $this->content->text = '<p class="text-muted">' . s(get_string('pluginname', 'block_edumasr_welcomebanner')) . '</p>';
        }

        return $this->content;
    }

    public function applicable_formats() {
        return ['my' => true, 'site' => true];
    }

    public function instance_allow_config() {
        return false;
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function hide_header() {
        return true;
    }
}

