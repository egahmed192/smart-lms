<?php
/**
 * One-off: insert local_odoo_sync scheduled tasks into DB (avoids manager class loading).
 * Run: php register_odoo_tasks.php (from moodle root). Delete after use.
 */
define('CLI_SCRIPT', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require(__DIR__ . '/config.php');
require_once($CFG->libdir . '/datalib.php');

global $DB;
$component = 'local_odoo_sync';
$now = time();
$tasks = [
    ['classname' => 'local_odoo_sync\task\sync_from_odoo',      'minute' => '*/15', 'hour' => '*', 'day' => '*', 'dayofweek' => '*', 'month' => '*'],
    ['classname' => 'local_odoo_sync\task\push_grades_to_odoo', 'minute' => '0',    'hour' => '*/6', 'day' => '*', 'dayofweek' => '*', 'month' => '*'],
    ['classname' => 'local_odoo_sync\task\license_expiry_reminder', 'minute' => '0', 'hour' => '9', 'day' => '*', 'dayofweek' => '*', 'month' => '*'],
];
foreach ($tasks as $t) {
    if ($DB->record_exists('task_scheduled', ['classname' => $t['classname']])) {
        echo "Already exists: {$t['classname']}\n";
        continue;
    }
    $record = (object)[
        'component' => $component,
        'classname' => $t['classname'],
        'lastruntime' => 0,
        'nextruntime' => $now,
        'minute' => $t['minute'],
        'hour' => $t['hour'],
        'day' => $t['day'],
        'dayofweek' => $t['dayofweek'],
        'month' => $t['month'],
        'faildelay' => 0,
        'customised' => 0,
        'disabled' => 0,
        'timestarted' => 0,
        'hostname' => null,
        'pid' => null,
    ];
    $DB->insert_record('task_scheduled', $record);
    echo "Inserted: {$t['classname']}\n";
}
echo "Done. Refresh Scheduled tasks page in Moodle.\n";
