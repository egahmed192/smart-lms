<?php
// This file is part of Moodle - http://moodle.org/
namespace block_edumasr_welcomebanner\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 * Main renderable for the Welcome banner block.
 */
class main implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER, $CFG;

        $welcomename = '';
        $showbanner = false;
        if (isloggedin() && !isguestuser()) {
            $welcomename = fullname($USER);
            $showbanner = true;
        }

        $scheduleurl = (new \moodle_url('/calendar/view.php', ['view' => 'upcoming']))->out(false);

        return [
            'showbanner' => $showbanner,
            'welcomename' => $welcomename,
            'scheduleurl' => $scheduleurl,
        ];
    }
}
