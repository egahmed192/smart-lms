<?php
namespace block_edumasr_recentcourses\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class main implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER, $PAGE;

        require_once($GLOBALS['CFG']->dirroot . '/course/lib.php');

        $renderer = $PAGE->get_renderer('core');
        $config = get_config('block_edumasr_recentcourses');
        $limit = isset($config->maxcourses) && $config->maxcourses > 0 ? (int) $config->maxcourses : 10;
        $displaycategories = !empty($config->displaycategories);

        $courses = course_get_recent_courses($USER->id, $limit, 0, 'timeaccess DESC');
        $recentcourses = [];

        foreach ($courses as $course) {
            \context_helper::preload_from_record($course);
            $context = \context_course::instance($course->id);
            $isfavourite = !empty($course->component);
            $exporter = new \core_course\external\course_summary_exporter(
                $course,
                ['context' => $context, 'isfavourite' => $isfavourite]
            );
            $data = (array) $exporter->export($renderer);
            $data['showcoursecategory'] = $displaycategories;
            $data['participantcount'] = count_enrolled_users($context, '', 0, true);
            $recentcourses[] = $data;
        }

        $nocoursesimgurl = $output->image_url('courses', 'block_recentlyaccessedcourses')->out(false);

        return [
            'courses' => $recentcourses,
            'nocoursesimgurl' => $nocoursesimgurl,
            'hascourses' => !empty($recentcourses),
        ];
    }
}

