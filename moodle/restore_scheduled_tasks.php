<?php
/**
 * One-off: restore all scheduled tasks (core + every plugin) into task_scheduled.
 * Run from moodle root: php restore_scheduled_tasks.php
 * Delete this file after use.
 */
define('CLI_SCRIPT', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require(__DIR__ . '/config.php');
require_once($CFG->libdir . '/classes/task/manager.php');

global $DB;

// 1) How many rows now
$count = $DB->count_records('task_scheduled');
echo "Current task_scheduled count: {$count}\n";

$components = ['moodle'];
$components = array_merge($components, \core_component::get_component_names(false));
$done = 0;
$errors = 0;
foreach ($components as $component) {
    try {
        \core\task\manager::reset_scheduled_tasks_for_component($component);
        $done++;
        if ($done % 20 === 0) {
            echo "Processed {$done} components...\n";
        }
    } catch (Throwable $e) {
        $errors++;
        echo "Skip [{$component}]: " . $e->getMessage() . "\n";
    }
}

$countAfter = $DB->count_records('task_scheduled');
echo "Done. Processed {$done} components, {$errors} errors. task_scheduled count: {$countAfter}\n";
echo "Refresh Scheduled tasks page in Moodle.\n";
