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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * One-off CLI script: assign the 'parent' role at system context
 * to every Moodle user that is in local_odoo_sync_map with odoo_type = 'parent'.
 *
 * Run from the Moodle public directory:
 *   php local/odoo_sync/cli/assign_parent_role.php
 */

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->dirroot . '/lib/accesslib.php');

global $DB;

// 1. Find the parent role by short name.
$role = $DB->get_record('role', ['shortname' => 'parent'], '*', MUST_EXIST);
cli_writeln("Found role: '{$role->shortname}' (id={$role->id})");

// 2. Get system context.
$sysctx = context_system::instance();

// 3. Load all mapped parent users from local_odoo_sync_map.
$maps = $DB->get_records('local_odoo_sync_map', ['odoo_type' => 'parent'], '', 'id, userid, odoo_id');
if (empty($maps)) {
    cli_writeln('No parent mappings found in local_odoo_sync_map. Nothing to do.');
    exit(0);
}

$total = count($maps);
cli_writeln("Found {$total} parent mappings. Assigning role...");

$assigned = 0;
$skipped = 0;
$errors = 0;

foreach ($maps as $map) {
    $userid = (int) $map->userid;

    // Skip if user doesn't exist or is deleted.
    $user = $DB->get_record('user', ['id' => $userid, 'deleted' => 0], 'id, username', IGNORE_MISSING);
    if (!$user) {
        cli_writeln("  SKIP: Moodle user {$userid} not found or deleted (odoo_id={$map->odoo_id})");
        $errors++;
        continue;
    }

    // Skip if role is already assigned at system level.
    if ($DB->record_exists('role_assignments', [
        'roleid'    => $role->id,
        'contextid' => $sysctx->id,
        'userid'    => $userid,
    ])) {
        cli_writeln("  SKIP (already has role): {$user->username} (userid={$userid})");
        $skipped++;
        continue;
    }

    // Assign the parent role at system context.
    try {
        role_assign($role->id, $userid, $sysctx->id);
        cli_writeln("  ASSIGNED: {$user->username} (userid={$userid}, odoo_id={$map->odoo_id})");
        $assigned++;
    } catch (Throwable $e) {
        cli_writeln("  ERROR assigning role to {$user->username} (userid={$userid}): " . $e->getMessage());
        $errors++;
    }
}

cli_writeln('');
cli_writeln("Done.");
cli_writeln("  Assigned : {$assigned}");
cli_writeln("  Skipped  : {$skipped} (already had role)");
cli_writeln("  Errors   : {$errors}");

exit(0);
