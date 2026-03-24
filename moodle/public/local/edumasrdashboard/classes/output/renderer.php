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
// along with Moodle. If not, see <http://www.moodle.org/licenses/>.

namespace local_edumasrdashboard\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Renderer for local_edumasrdashboard.
 */
class renderer extends \plugin_renderer_base {

    /**
     * Render the courses page.
     *
     * @param courses_page $page
     * @return string
     */
    public function render_courses_page(courses_page $page): string {
        $data = $page->export_for_template($this);
        return $this->render_from_template('local_edumasrdashboard/courses_page', $data);
    }
}

