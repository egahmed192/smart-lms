<?php
// This file is part of Moodle - http://moodle.org/
namespace block_edumasr_welcomebanner\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;

/**
 * Renderer for the Welcome banner block.
 */
class renderer extends plugin_renderer_base {

    /**
     * Render the main block content.
     *
     * @param main $main
     * @return string
     */
    public function render_main(main $main) {
        return $this->render_from_template('block_edumasr_welcomebanner/main', $main->export_for_template($this));
    }
}
