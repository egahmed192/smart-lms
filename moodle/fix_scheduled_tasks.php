<?php
/**
 * 1) Remove the 3 local_odoo_sync task rows we inserted (in case they break the Scheduled tasks page).
 * 2) Repopulate all scheduled tasks (core + plugins) so the table is correct.
 * Run from moodle root: php fix_scheduled_tasks.php
 */
define('CLI_SCRIPT', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '512M');
require(__DIR__ . '/config.php');
require_once($CFG->libdir . '/classes/task/manager.php');

global $DB;

$odooClassnames = [
    'local_odoo_sync\\task\\sync_from_odoo',
    'local_odoo_sync\\task\\push_grades_to_odoo',
    'local_odoo_sync\\task\\license_expiry_reminder',
];
foreach ($odooClassnames as $cn) {
    $deleted = $DB->delete_records('task_scheduled', ['classname' => $cn]);
    if ($deleted) {
        echo "Removed from DB: {$cn}\n";
    }
}
$count = $DB->count_records('task_scheduled');
echo "task_scheduled count after removal: {$count}\n";

$components = array_merge(['moodle'], \core_component::get_component_names(false));
$done = 0;
$errors = 0;
foreach ($components as $component) {
    try {
        \core\task\manager::reset_scheduled_tasks_for_component($component);
        $done++;
    } catch (Throwable $e) {
        $errors++;
        echo "Skip [{$component}]: " . $e->getMessage() . "\n";
    }
}
$countAfter = $DB->count_records('task_scheduled');
echo "Done. Components: {$done}, errors: {$errors}. task_scheduled count: {$countAfter}\n";
echo "Refresh: Site administration -> Server -> Scheduled tasks\n";
