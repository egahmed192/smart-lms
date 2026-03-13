<?php
/**
 * One-off: formally (re)register local_odoo_sync scheduled tasks using Moodle's task manager.
 * Run from Moodle root: php reset_odoo_tasks.php
 * Safe now that sync_from_odoo fatal error is fixed.
 */
define('CLI_SCRIPT', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require(__DIR__ . '/config.php');
require_once($CFG->libdir . '/classes/task/manager.php');

echo "Resetting scheduled tasks for component local_odoo_sync...\n";

try {
    \core\task\manager::reset_scheduled_tasks_for_component('local_odoo_sync');
    echo "Done. local_odoo_sync tasks have been (re)registered.\n";
    echo "Go to Site administration -> Server -> Scheduled tasks and search for \"odoo\".\n";
} catch (Throwable $e) {
    echo "Error while resetting tasks: " . $e->getMessage() . "\n";
}

