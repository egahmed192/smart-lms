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
 * Enrich Odoo credentials CSV with Moodle username/email using odoo_id.
 *
 * Default input/output:
 *   local/odoo_sync/cli/odoo_lms_credentials.csv
 *
 * Example:
 *   php local/odoo_sync/cli/enrich_credentials_csv.php
 *   php local/odoo_sync/cli/enrich_credentials_csv.php --output=/path/new.csv
 */

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

global $DB;

$help = "Enrich credentials CSV with Moodle username/email by odoo_id.\n\n" .
    "Options:\n" .
    "  --input=PATH   Input CSV path (default: local/odoo_sync/cli/odoo_lms_credentials.csv)\n" .
    "  --output=PATH  Output CSV path (default: overwrite input with backup)\n" .
    "  --help         Print this help\n";

[$options] = cli_get_params(
    ['input' => null, 'output' => null, 'help' => false],
    ['h' => 'help']
);

if (!empty($options['help'])) {
    echo $help;
    exit(0);
}

$defaultpath = $CFG->dirroot . '/local/odoo_sync/cli/odoo_lms_credentials.csv';
$inputpath = !empty($options['input']) ? $options['input'] : $defaultpath;
$outputpath = !empty($options['output']) ? $options['output'] : $inputpath;

if (!file_exists($inputpath)) {
    cli_error("Input CSV not found: {$inputpath}");
}

$in = fopen($inputpath, 'r');
if ($in === false) {
    cli_error("Unable to open input CSV: {$inputpath}");
}

$header = fgetcsv($in);
if (!is_array($header) || empty($header)) {
    fclose($in);
    cli_error("Input CSV is empty or invalid: {$inputpath}");
}

$typeidx = array_search('type', $header, true);
$odooidx = array_search('odoo_id', $header, true);
$useridx = array_search('moodle_username', $header, true);
$emailidx = array_search('moodle_email', $header, true);

if ($odooidx === false) {
    fclose($in);
    cli_error("Required 'odoo_id' column is missing.");
}
if ($typeidx === false) {
    fclose($in);
    cli_error("Required 'type' column is missing.");
}
if ($useridx === false) {
    fclose($in);
    cli_error("Required 'moodle_username' column is missing.");
}

if ($emailidx === false) {
    $header[] = 'moodle_email';
    $emailidx = count($header) - 1;
}

$rows = [];
$odooids = [];

while (($row = fgetcsv($in)) !== false) {
    if (count($row) < count($header) - 1) {
        // Normalize malformed short rows so index access stays safe.
        $row = array_pad($row, count($header), '');
    } else if (count($row) < count($header)) {
        $row[] = '';
    }

    $rawid = trim((string)($row[$odooidx] ?? ''));
    if ($rawid !== '' && ctype_digit($rawid)) {
        $odooids[(int)$rawid] = true;
    }
    $rows[] = $row;
}
fclose($in);

if (empty($rows)) {
    cli_writeln('No data rows in CSV. Nothing to update.');
    exit(0);
}

$lookup = [];
if (!empty($odooids)) {
    [$insql, $params] = $DB->get_in_or_equal(array_keys($odooids), SQL_PARAMS_NAMED);
    $sql = "SELECT m.odoo_id, m.odoo_type, u.username, u.email
              FROM {local_odoo_sync_map} m
              JOIN {user} u ON u.id = m.userid
             WHERE m.odoo_id {$insql}
               AND u.deleted = 0";
    $records = $DB->get_records_sql($sql, $params);

    foreach ($records as $record) {
        $type = trim((string)$record->odoo_type);
        $id = (int)$record->odoo_id;
        $lookup[$type . ':' . $id] = [
            'username' => (string)$record->username,
            'email' => (string)$record->email,
        ];
        // Fallback when CSV type is missing/unexpected.
        if (!isset($lookup['*:' . $id])) {
            $lookup['*:' . $id] = [
                'username' => (string)$record->username,
                'email' => (string)$record->email,
            ];
        }
    }
}

$updated = 0;
$missing = 0;

foreach ($rows as &$row) {
    $type = trim((string)($row[$typeidx] ?? ''));
    $rawid = trim((string)($row[$odooidx] ?? ''));
    $id = ctype_digit($rawid) ? (int)$rawid : 0;

    if ($id === 0) {
        $missing++;
        continue;
    }

    $key = $type . ':' . $id;
    $item = $lookup[$key] ?? $lookup['*:' . $id] ?? null;
    if ($item === null) {
        $missing++;
        continue;
    }

    // Keep this deterministic from Moodle DB.
    $row[$useridx] = $item['username'];
    $row[$emailidx] = $item['email'];
    $updated++;
}
unset($row);

if ($outputpath === $inputpath) {
    $backup = $inputpath . '.bak-' . date('Ymd-His');
    if (!copy($inputpath, $backup)) {
        cli_error("Failed to create backup before overwrite: {$backup}");
    }
    cli_writeln("Backup created: {$backup}");
}

$out = fopen($outputpath, 'w');
if ($out === false) {
    cli_error("Unable to open output CSV for writing: {$outputpath}");
}

fputcsv($out, $header);
foreach ($rows as $row) {
    if (count($row) < count($header)) {
        $row = array_pad($row, count($header), '');
    }
    fputcsv($out, $row);
}
fclose($out);

cli_writeln("Updated rows: {$updated}");
cli_writeln("Rows not matched by odoo_id/type: {$missing}");
cli_writeln("Output CSV: {$outputpath}");

