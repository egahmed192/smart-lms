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

if ($hassiteconfig && has_capability('local/message_audit:view_logs', context_system::instance())) {
    $ADMIN->add('reports', new admin_externalpage(
        'local_message_audit',
        get_string('pluginname', 'local_message_audit'),
        new moodle_url('/local/message_audit/index.php'),
        'local/message_audit:view_logs'
    ));
    $ADMIN->add('reports', new admin_externalpage(
        'local_message_audit_bulk',
        get_string('bulk_message', 'local_message_audit'),
        new moodle_url('/local/message_audit/bulk.php'),
        'local/message_audit:send_bulk_message'
    ));
}
