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

namespace local_message_audit;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/message_audit/classes/observer.php');

/**
 * Tests for message_audit observer (log and flag).
 */
class observer_test extends \advanced_testcase {

    public function test_message_sent_logs(): void {
        global $DB;
        $this->resetAfterTest();
        $u1 = $this->getDataGenerator()->create_user();
        $u2 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $before = $DB->count_records('local_message_audit_log');
        $event = \core\event\message_sent::create([
            'objectid' => 1,
            'userid' => $u1->id,
            'relateduserid' => $u2->id,
            'context' => \context_system::instance(),
            'other' => ['courseid' => $course->id],
        ]);
        $event->trigger();
        $after = $DB->count_records('local_message_audit_log');
        $this->assertGreaterThan($before, $after);
        $log = $DB->get_record_sql("SELECT * FROM {local_message_audit_log} ORDER BY id DESC LIMIT 1");
        $this->assertEquals($u1->id, $log->senderid);
        $this->assertEquals($u2->id, $log->receiverid);
    }

    public function test_message_sent_flagged_by_keyword(): void {
        global $DB;
        $this->resetAfterTest();
        $DB->insert_record('local_message_audit_keywords', (object)[
            'pattern' => 'badword',
            'severity' => 'high',
            'action' => 'flag',
            'timemodified' => time(),
        ]);
        $u1 = $this->getDataGenerator()->create_user();
        $u2 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $msgid = $DB->insert_record('messages', (object)[
            'useridfrom' => $u1->id,
            'useridto' => $u2->id,
            'subject' => 'Test',
            'fullmessage' => 'This has badword in it',
            'smallmessage' => 'This has badword',
            'timecreated' => time(),
        ]);
        $event = \core\event\message_sent::create([
            'objectid' => $msgid,
            'userid' => $u1->id,
            'relateduserid' => $u2->id,
            'context' => \context_system::instance(),
            'other' => ['courseid' => $course->id],
        ]);
        $event->trigger();
        $log = $DB->get_record_sql("SELECT * FROM {local_message_audit_log} ORDER BY id DESC LIMIT 1");
        $this->assertEquals(1, $log->flagged);
        $this->assertStringContainsString('badword', $log->reason);
    }
}
