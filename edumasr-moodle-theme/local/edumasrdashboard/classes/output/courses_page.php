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

namespace local_edumasrdashboard\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 * Output component for the custom My Courses page (card grid, tabs, pagination).
 */
class courses_page implements renderable, templatable {

    /** @var string Tab filter: all, inprogress, completed, archived */
    protected $filter;
    /** @var int Page number (1-based) */
    protected $page;
    /** @var int Courses per page */
    protected $perpage;

    public function __construct(string $filter = 'all', int $page = 1, int $perpage = 6) {
        $this->filter = $filter;
        $this->page = max(1, $page);
        $this->perpage = max(1, min(24, $perpage));
    }

    /**
     * Get the first instructor/teacher name for a course.
     */
    protected static function get_course_instructor_name(int $courseid): string {
        global $DB;
        $context = \context_course::instance($courseid);
        $teacherroles = get_archetype_roles('editingteacher');
        $teacherroles += get_archetype_roles('teacher');
        if (empty($teacherroles)) {
            return '';
        }
        $roleids = array_keys($teacherroles);
        list($insql, $params) = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED);
        $params['contextid'] = $context->id;
        $sql = "SELECT u.id, u.firstname, u.lastname
                  FROM {user} u
                  JOIN {role_assignments} ra ON ra.userid = u.id
                 WHERE ra.contextid = :contextid AND ra.roleid $insql
                   AND u.deleted = 0
                 ORDER BY ra.id
                 LIMIT 1";
        $user = $DB->get_record_sql($sql, $params);
        return $user ? fullname($user) : '';
    }

    public function export_for_template(renderer_base $output): array {
        global $USER, $PAGE, $CFG;

        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->libdir . '/enrollib.php');

        $renderer = $PAGE->get_renderer('core');
        $courses = enrol_get_my_courses(['fullname', 'shortname', 'category', 'visible'], 'fullname ASC');

        $all = [];
        foreach ($courses as $course) {
            $course = get_course($course->id);
            if (!$course || !$course->visible) {
                continue;
            }
            \context_helper::preload_from_record($course);
            $context = \context_course::instance($course->id);
            try {
                $exporter = new \core_course\external\course_summary_exporter(
                    $course,
                    ['context' => $context, 'isfavourite' => false]
                );
                $data = (array) $exporter->export($renderer);
            } catch (\Throwable $e) {
                continue;
            }
            $progress = isset($data['progress']) ? (int) $data['progress'] : 0;
            $data['progress'] = $progress;
            $data['completed'] = ($progress >= 100);
            $data['instructor'] = self::get_course_instructor_name($course->id);
            $img = $data['courseimage'] ?? $output->get_generated_image_for_id($course->id);
            $data['courseimage'] = is_object($img) ? $img->out(false) : (string) $img;
            $all[] = $data;
        }

        $inprogress = array_values(array_filter($all, function ($c) {
            return !$c['completed'] && !empty($c['hasprogress']);
        }));
        $completed = array_values(array_filter($all, function ($c) {
            return $c['completed'];
        }));
        $archived = [];

        switch ($this->filter) {
            case 'inprogress':
                $filtered = $inprogress;
                break;
            case 'completed':
                $filtered = $completed;
                break;
            case 'archived':
                $filtered = $archived;
                break;
            default:
                $filtered = $all;
        }

        $total = count($filtered);
        $offset = ($this->page - 1) * $this->perpage;
        $paged = array_slice($filtered, $offset, $this->perpage);
        $numpages = $total ? (int) ceil($total / $this->perpage) : 1;

        $baseurl = (new \moodle_url('/local/edumasrdashboard/courses.php'))->out(false);
        $q = '?filter=' . $this->filter;
        $pagination = [
            'page' => $this->page,
            'perpage' => $this->perpage,
            'total' => $total,
            'numpages' => $numpages,
            'filter' => $this->filter,
            'prevpage' => $this->page > 1 ? $this->page - 1 : null,
            'nextpage' => $this->page < $numpages ? $this->page + 1 : null,
            'prevurl' => $this->page > 1 ? $baseurl . $q . '&page=' . ($this->page - 1) : null,
            'nexturl' => $this->page < $numpages ? $baseurl . $q . '&page=' . ($this->page + 1) : null,
            'pages' => [],
        ];
        for ($i = 1; $i <= $numpages; $i++) {
            $pagination['pages'][] = [
                'num' => $i,
                'active' => ($i === $this->page),
                'url' => $baseurl . $q . '&page=' . $i,
            ];
        }

        $tabs = [
            ['id' => 'all', 'label' => get_string('allcourses', 'local_edumasrdashboard'), 'active' => ($this->filter === 'all'), 'url' => $baseurl . '?filter=all'],
            ['id' => 'inprogress', 'label' => get_string('inprogress', 'local_edumasrdashboard'), 'active' => ($this->filter === 'inprogress'), 'count' => count($inprogress), 'url' => $baseurl . '?filter=inprogress'],
            ['id' => 'completed', 'label' => get_string('completed', 'local_edumasrdashboard'), 'active' => ($this->filter === 'completed'), 'count' => count($completed), 'url' => $baseurl . '?filter=completed'],
            ['id' => 'archived', 'label' => get_string('archived', 'local_edumasrdashboard'), 'active' => ($this->filter === 'archived'), 'count' => count($archived), 'url' => $baseurl . '?filter=archived'],
        ];

        return [
            'title' => get_string('mycourses', 'moodle'),
            'subtitle' => get_string('coursesinprogress', 'local_edumasrdashboard', count($inprogress)),
            'filter' => $this->filter,
            'baseurl' => $baseurl,
            'tabs' => $tabs,
            'courses' => $paged,
            'hascourses' => !empty($paged),
            'pagination' => $pagination,
            'showpagination' => $numpages > 1,
        ];
    }
}
