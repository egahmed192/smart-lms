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

namespace local_odoo_sync\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Push course total scores from Moodle to Odoo (when Odoo API supports it).
 * Implement the actual API call when the client provides the endpoint.
 */
class push_grades_to_odoo extends \core\task\scheduled_task {

    public function get_name(): string {
        return get_string('task_push_grades', 'local_odoo_sync');
    }

    public function execute(): void {
        if (!function_exists('local_assessments_get_course_totals')) {
            return;
        }
        // When Odoo provides a grades endpoint (e.g. POST /api/lms/grade/update),
        // iterate students with sync map, get course totals via local_assessments_get_course_totals,
        // and push each student's totals to Odoo.
        mtrace('Grades push: Odoo grade API not yet configured. Skipping.');
    }
}
