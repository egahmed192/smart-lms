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
// along with Moodle.  If not, see <http://www.moodle.org/licenses/>.

/**
 * Custom My Courses page – card grid, filter tabs, pagination.
 *
 * @package    local_edumasrdashboard
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

require_login(null, false);

$filter = optional_param('filter', 'all', PARAM_ALPHANUM);
$page = optional_param('page', 1, PARAM_INT);
$perpage = 6;

$validfilters = ['all', 'inprogress', 'completed', 'archived'];
if (!in_array($filter, $validfilters, true)) {
    $filter = 'all';
}

$url = new \moodle_url('/local/edumasrdashboard/courses.php', ['filter' => $filter]);
if ($page > 1) {
    $url->param('page', $page);
}

$PAGE->set_url($url);
$PAGE->set_context(\context_user::instance($USER->id));
$PAGE->set_pagelayout('mycourses');
$PAGE->set_title(get_string('mycourses', 'moodle'));
$PAGE->set_heading(get_string('mycourses', 'moodle'));
$PAGE->add_body_classes(['page-mycourses', 'edumasr-courses-view']);

$output = $PAGE->get_renderer('local_edumasrdashboard');
$renderable = new \local_edumasrdashboard\output\courses_page($filter, $page, $perpage);

echo $OUTPUT->header();
echo $output->render($renderable);
echo $OUTPUT->footer();
