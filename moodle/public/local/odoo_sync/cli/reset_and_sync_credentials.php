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
 * One-off CLI script to reset passwords for all Odoo-synced students and parents,
 * update Moodle with the new hashes, and push the plain passwords to Odoo.
 *
 * It also writes a CSV file with the generated credentials for testing:
 *   local/odoo_sync/cli/odoo_lms_credentials.csv
 *
 * WARNING: The CSV contains plain passwords. Treat it as highly sensitive and
 * delete it when you are done testing.
 */

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->dirroot . '/local/odoo_sync/classes/odoo_client.php');
require_once($CFG->dirroot . '/user/lib.php');

global $DB;

$apiurl = get_config('local_odoo_sync', 'apiurl');
$apiuser = get_config('local_odoo_sync', 'apiuser');
$apipassword = get_config('local_odoo_sync', 'apipassword');

if (empty($apiurl) || empty($apiuser) || empty($apipassword)) {
    cli_writeln('Odoo sync: API not configured. Aborting.');
    exit(1);
}

cli_writeln("Connecting to Odoo at {$apiurl} ...");

try {
    $client = new \local_odoo_sync\odoo_client($apiurl, $apiuser, $apipassword);
    $client->login();
} catch (Throwable $e) {
    cli_writeln('Odoo sync: Login failed: ' . $e->getMessage());
    exit(1);
}

// Prepare CSV output file.
$csvpath = $CFG->dirroot . '/local/odoo_sync/cli/odoo_lms_credentials.csv';
$fh = fopen($csvpath, 'w');
if ($fh === false) {
    cli_writeln('Could not open CSV file for writing: ' . $csvpath);
    exit(1);
}
// Header row.
fputcsv($fh, ['type', 'moodle_userid', 'moodle_username', 'odoo_id', 'plain_password', 'status', 'message']);

// Fetch all mappings (students and parents).
$maps = $DB->get_records('local_odoo_sync_map', null, '', 'id, userid, odoo_id, odoo_type');
if (!$maps) {
    echo "No mappings found in local_odoo_sync_map. Nothing to do.\n";
    fclose($fh);
    exit(0);
}

$total = count($maps);
$processed = 0;
$errors = 0;

echo "Found {$total} Odoo-synced users.\n";

foreach ($maps as $map) {
    $processed++;
    $type = $map->odoo_type;
    if ($type !== 'student' && $type !== 'parent') {
        // Skip other types, just in case.
        continue;
    }

    $user = $DB->get_record('user', ['id' => $map->userid, 'deleted' => 0], '*', IGNORE_MISSING);
    if (!$user) {
        $errors++;
        $msg = "Moodle user {$map->userid} not found or deleted; skipping.";
        echo $msg . "\n";
        fputcsv($fh, [$type, $map->userid, '', $map->odoo_id, '', 'error', $msg]);
        continue;
    }

    // Generate a new random password.
    $plainpassword = generate_password(12);

    // Keep synced accounts as local users so they can log in via Moodle login form.
    $user->mnethostid = (int)$CFG->mnet_localhost_id;
    // Update Moodle hashed password.
    $user->password = hash_internal_user_password($plainpassword);
    try {
        user_update_user($user, false, false);
    } catch (Throwable $e) {
        $errors++;
        $msg = 'Failed to update Moodle password: ' . $e->getMessage();
        echo $msg . " (userid={$user->id})\n";
        fputcsv($fh, [$type, $user->id, $user->username, $map->odoo_id, $plainpassword, 'error', $msg]);
        continue;
    }

    // Push plain credentials to Odoo.
    try {
        if ($type === 'student') {
            $client->student_update((int)$map->odoo_id, $user->username, $plainpassword);
        } else { // parent
            $client->parent_update((int)$map->odoo_id, $user->username, $plainpassword);
        }
        $msg = 'OK';
        echo "Updated {$type} (Moodle user {$user->id}, username {$user->username}, Odoo id {$map->odoo_id})\n";
        fputcsv($fh, [$type, $user->id, $user->username, $map->odoo_id, $plainpassword, 'success', $msg]);
    } catch (Throwable $e) {
        $errors++;
        $msg = 'Failed to update Odoo credentials: ' . $e->getMessage();
        echo $msg . " (type={$type}, Moodle user {$user->id}, Odoo id {$map->odoo_id})\n";
        fputcsv($fh, [$type, $user->id, $user->username, $map->odoo_id, $plainpassword, 'error', $msg]);
        continue;
    }
}

fclose($fh);

echo "Done. Processed {$processed} mappings with {$errors} errors.\n";
echo "Credentials CSV written to: {$csvpath}\n";

exit(0);

