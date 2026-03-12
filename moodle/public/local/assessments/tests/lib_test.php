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

namespace local_assessments;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/assessments/lib.php');

/**
 * Tests for assessments lib.
 */
class lib_test extends \advanced_testcase {

    public function test_get_secret_code_creates_and_returns(): void {
        global $DB;
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $code = local_assessments_get_secret_code($user->id);
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{4}$/', $code);
        $this->assertSame($code, local_assessments_get_secret_code($user->id));
    }

    public function test_get_course_totals_empty(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $totals = local_assessments_get_course_totals($user->id);
        $this->assertIsArray($totals);
        $this->assertCount(0, $totals);
    }

    public function test_get_course_totals_with_data(): void {
        global $DB;
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $student = $this->getDataGenerator()->create_user();
        $teacher = $this->getDataGenerator()->create_user();
        $sysctx = \context_system::instance();
        $ctx = \context_course::instance($course->id);
        $roleid = $DB->get_field('role', 'id', ['shortname' => 'teacher']);
        if ($roleid) {
            role_assign($roleid, $teacher->id, $ctx->id);
        }
        $this->getDataGenerator()->enrol_user($student->id, $course->id, 'student');
        $aid = $DB->insert_record('local_assessments', (object)[
            'publicid' => 'ASS-TEST-1',
            'type' => 'public',
            'name' => 'Test',
            'fullmark' => 100,
            'weight' => 1,
            'courseid' => $course->id,
            'createdby' => $teacher->id,
            'timecreated' => time(),
            'timemodified' => time(),
        ]);
        $DB->insert_record('local_assessments_eval', (object)[
            'studentid' => $student->id,
            'assessmentid' => $aid,
            'mark' => 80,
            'datecreated' => time(),
        ]);
        $totals = local_assessments_get_course_totals($student->id);
        $this->assertArrayHasKey($course->id, $totals);
        $this->assertEqualsWithDelta(0.8, $totals[$course->id], 0.01, 'Total should be 80/100 * 1');
    }

    public function test_can_see_grades_announcement_future(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $assessment = (object)[
            'courseid' => $course->id,
            'announcementdate' => time() + 86400,
        ];
        $this->assertFalse(local_assessments_can_see_grades($assessment));
    }

    public function test_can_see_grades_announcement_past(): void {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $assessment = (object)[
            'courseid' => $course->id,
            'announcementdate' => time() - 86400,
        ];
        $this->assertTrue(local_assessments_can_see_grades($assessment));
    }
}
