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

namespace local_parent_portal;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/parent_portal/lib.php');

/**
 * Tests for parent portal helper functions.
 */
class parent_portal_test extends \advanced_testcase {

    public function test_get_students_for_parent_empty(): void {
        $this->resetAfterTest();
        $parent = $this->getDataGenerator()->create_user();
        $students = local_parent_portal_get_students_for_parent($parent->id);
        $this->assertIsArray($students);
        $this->assertCount(0, $students);
    }

    public function test_is_parent_of_false(): void {
        $this->resetAfterTest();
        $parent = $this->getDataGenerator()->create_user();
        $student = $this->getDataGenerator()->create_user();
        $this->assertFalse(local_parent_portal_is_parent_of($parent->id, $student->id));
    }

    public function test_is_parent_of_true(): void {
        global $DB;
        $this->resetAfterTest();
        $parent = $this->getDataGenerator()->create_user();
        $student = $this->getDataGenerator()->create_user();
        $DB->insert_record('local_parent_portal_rel', (object)[
            'parent_userid' => $parent->id,
            'student_userid' => $student->id,
            'active' => 1,
            'timecreated' => time(),
            'source' => 'manual',
        ]);
        $this->assertTrue(local_parent_portal_is_parent_of($parent->id, $student->id));
        $this->assertCount(1, local_parent_portal_get_students_for_parent($parent->id));
    }
}
