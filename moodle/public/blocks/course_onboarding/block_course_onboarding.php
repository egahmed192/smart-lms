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

class block_course_onboarding extends block_base {

    public function init(): void {
        $this->title = get_string('title', 'block_course_onboarding');
    }

    public function applicable_formats(): array {
        return [
            'course-view' => true,
            'site' => false,
            'my' => false,
        ];
    }

    public function instance_allow_multiple(): bool {
        return false;
    }

    public function has_config(): bool {
        return false;
    }

    public function get_content() {
        global $COURSE, $OUTPUT;

        if (isset($this->content)) {
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->footer = '';

        if (empty($COURSE) || empty($COURSE->id) || $COURSE->id == SITEID) {
            $this->content->text = '';
            return $this->content;
        }

        $context = context_course::instance($COURSE->id);
        $canconfigure = has_capability('block/course_onboarding:configure', $context);

        $nextcmid = isset($this->config->nextcmid) ? (int)$this->config->nextcmid : 0;
        $rules = $this->config->rules ?? null;
        $ruleshtml = '';
        if (is_array($rules) && isset($rules['text'])) {
            $ruleshtml = format_text($rules['text'], $rules['format'] ?? FORMAT_HTML, ['context' => $context]);
        } else if (is_string($rules)) {
            $ruleshtml = format_text($rules, FORMAT_HTML, ['context' => $context]);
        }

        $nextactivity = null;
        if ($nextcmid > 0) {
            $modinfo = get_fast_modinfo($COURSE);
            if (isset($modinfo->cms[$nextcmid])) {
                $cm = $modinfo->cms[$nextcmid];
                if ($cm->uservisible) {
                    $nextactivity = [
                        'name' => $cm->get_formatted_name(),
                        'url' => $cm->url ? $cm->url->out(false) : '',
                    ];
                }
            }
        }

        if (!$nextactivity && trim(strip_tags($ruleshtml)) === '') {
            $this->content->text = $canconfigure
                ? $OUTPUT->notification('Configure this block to pin the next activity and add course rules.', 'info')
                : '';
            return $this->content;
        }

        $data = [
            'nextactivity' => $nextactivity,
            'hasnextactivity' => !empty($nextactivity),
            'ruleshtml' => $ruleshtml,
            'hasrules' => trim(strip_tags($ruleshtml)) !== '',
        ];

        $this->content->text = $OUTPUT->render_from_template('block_course_onboarding/main', $data);
        return $this->content;
    }
}

