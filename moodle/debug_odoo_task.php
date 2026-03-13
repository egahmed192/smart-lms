<?php
// Debug script: try to load Odoo task classes in isolation.
define('CLI_SCRIPT', true);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require(__DIR__ . '/config.php');

echo "Start debug\n";

try {
    $t1 = new \local_odoo_sync\task\sync_from_odoo();
    echo "Loaded sync_from_odoo\n";
} catch (Throwable $e) {
    echo "sync_from_odoo error: " . $e->getMessage() . "\n";
}

try {
    $t2 = new \local_odoo_sync\task\push_grades_to_odoo();
    echo "Loaded push_grades_to_odoo\n";
} catch (Throwable $e) {
    echo "push_grades_to_odoo error: " . $e->getMessage() . "\n";
}

try {
    $t3 = new \local_odoo_sync\task\license_expiry_reminder();
    echo "Loaded license_expiry_reminder\n";
} catch (Throwable $e) {
    echo "license_expiry_reminder error: " . $e->getMessage() . "\n";
}

echo "Done\n";

