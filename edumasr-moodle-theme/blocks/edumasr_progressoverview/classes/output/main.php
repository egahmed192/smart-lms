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

            // Recalculate progress explicitly for current user so completion updates are always reflected.
            $progresspct = \core_completion\progress::get_course_progress_percentage($course, $USER->id);
            $data['hasprogress'] = ($progresspct !== null);
            $data['progress'] = $data['hasprogress'] ? (int) round($progresspct) : 0;
            $data['showcoursecategory'] = $displaycategories;

            // Activity progression: list activities with completion enabled and their completion state.
            $data['activities'] = [];
            if ($data['hasprogress']) {
                $completion = new \completion_info($course);
                $activities = $completion->get_activities();
                foreach ($activities as $cm) {
                    if (!$cm->uservisible) {
                        continue;
                    }
                    $details = \core_completion\cm_completion_details::get_instance($cm, $USER->id, false);
                    $complete = $details->is_overall_complete();
                    $data['activities'][] = [
                        'name' => $cm->get_formatted_name(),
                        'url' => $cm->url->out(false),
                        'complete' => $complete,
                        'progress' => $complete ? 100 : 0,
                    ];
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
