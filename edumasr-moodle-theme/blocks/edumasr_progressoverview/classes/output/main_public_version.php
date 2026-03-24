<?php
namespace block_edumasr_progressoverview\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class main implements renderable, templatable {

    public function export_for_template(renderer_base $output) {
        global $USER, $PAGE, $CFG;

        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->libdir . '/enrollib.php');
        require_once($CFG->libdir . '/completionlib.php');

        $renderer = $PAGE->get_renderer('core');
        $config = get_config('block_edumasr_progressoverview');
        $limit = isset($config->maxcourses) && $config->maxcourses > 0 ? (int) $config->maxcourses : 10;
        $displaycategories = !empty($config->displaycategories);

        $courses = enrol_get_my_courses(null, 'fullname ASC', $limit);
        $courseprogress = [];

        foreach ($courses as $course) {
            $course = get_course($course->id);
            if (!$course) {
                continue;
            }
            \context_helper::preload_from_record($course);
            $context = \context_course::instance($course->id);
            $isfavourite = false;

            try {
                $exporter = new \core_course\external\course_summary_exporter(
                    $course,
                    ['context' => $context, 'isfavourite' => $isfavourite]
                );
                $data = (array) $exporter->export($renderer);
            } catch (\Exception $e) {
                continue;
            }

            $data['showcoursecategory'] = $displaycategories;
            $data['progress'] = isset($data['progress']) ? (int) $data['progress'] : 0;
            if (!isset($data['hasprogress']) || !$data['hasprogress']) {
                $data['hasprogress'] = false;
                $data['progress'] = 0;
            }

            // Activities with completion below the course name.
            $data['activities'] = [];
            if (!empty($course->enablecompletion)) {
                $completioninfo = new \completion_info($course);
                if ($completioninfo->is_enabled()) {
                    $visible = $completioninfo->get_user_activities_with_completion($USER->id);
                    foreach ($completioninfo->get_activities() as $cm) {
                        if (!isset($visible[$cm->id]) || !$cm->uservisible) {
                            continue;
                        }
                        $cmdata = $completioninfo->get_data($cm, false, $USER->id);
                        $complete = !empty($cmdata->completionstate) && $cmdata->completionstate >= COMPLETION_COMPLETE;
                        $data['activities'][] = (object) [
                            'name' => $cm->get_formatted_name(),
                            'complete' => $complete,
                            'url' => $cm->url ? $cm->url->out(false) : '',
                        ];
                    }
                }
            }
            $data['hasactivities'] = !empty($data['activities']);

            $courseprogress[] = $data;
        }

        return [
            'courses' => $courseprogress,
            'hascourses' => !empty($courseprogress),
        ];
    }
}
