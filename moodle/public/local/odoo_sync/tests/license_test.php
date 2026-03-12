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

namespace local_odoo_sync;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/odoo_sync/lib.php');

/**
 * Tests for license validation.
 */
class license_test extends \advanced_testcase {

    public function test_is_license_valid_no_record(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $this->assertTrue(local_odoo_sync_is_license_valid($user->id));
    }

    public function test_is_license_valid_expired(): void {
        global $DB;
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $DB->insert_record('local_odoo_sync_lic', (object)[
            'userid' => $user->id,
            'license_expiry' => time() - 86400,
            'license_status' => 'active',
            'timemodified' => time(),
        ]);
        $this->assertFalse(local_odoo_sync_is_license_valid($user->id));
    }

    public function test_is_license_valid_active_future(): void {
        global $DB;
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $DB->insert_record('local_odoo_sync_lic', (object)[
            'userid' => $user->id,
            'license_expiry' => time() + 86400,
            'license_status' => 'active',
            'timemodified' => time(),
        ]);
        $this->assertTrue(local_odoo_sync_is_license_valid($user->id));
    }

    public function test_is_license_valid_status_not_active(): void {
        global $DB;
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $DB->insert_record('local_odoo_sync_lic', (object)[
            'userid' => $user->id,
            'license_expiry' => time() + 86400,
            'license_status' => 'expired',
            'timemodified' => time(),
        ]);
        $this->assertFalse(local_odoo_sync_is_license_valid($user->id));
    }
}
