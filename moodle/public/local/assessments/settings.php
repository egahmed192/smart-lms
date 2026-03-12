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

if ($hassiteconfig && has_capability('local/assessments:view_secret_codes', context_system::instance())) {
    $ADMIN->add('reports', new admin_externalpage(
        'local_assessments_export_codes',
        get_string('export_secret_codes', 'local_assessments'),
        new moodle_url('/local/assessments/export_codes.php'),
        'local/assessments:view_secret_codes'
    ));
}
