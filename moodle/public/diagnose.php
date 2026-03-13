<?php
/**
 * One-off diagnostic script to find why Moodle causes ERR_CONNECTION_RESET.
 * Delete this file after fixing the issue.
 * Access: http://localhost:8080/moodle/diagnose.php
 */
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "=== Moodle connection diagnostic ===\n\n";

// 1. PHP
echo "PHP version: " . PHP_VERSION . "\n";

// 2. Paths (without loading full config)
$moodleroot = dirname(__DIR__);  // moodle/
$publicroot = __DIR__;           // moodle/public/
echo "moodle root (dirname __DIR__): " . $moodleroot . "\n";
echo "public root (__DIR__): " . $publicroot . "\n";

// 3. Config file
$configpath = $moodleroot . '/config.php';
echo "\nConfig file exists: " . (file_exists($configpath) ? 'yes' : 'NO') . " ($configpath)\n";

if (!file_exists($configpath)) {
    echo "Stop: no config.php.\n";
    exit;
}

// 4. Read config (parse manually to avoid loading setup.php which may crash)
$config = file_get_contents($configpath);
$dbhost = 'localhost';
$dbport = 3307;
$dbname = 'moodle';
$dbuser = 'root';
$dbpass = '123456';
if (preg_match("/dbhost\s*=\s*'([^']*)'/", $config, $m)) $dbhost = $m[1];
if (preg_match("/dbport['\s]*=>\s*'?(\d+)'/", $config, $m)) $dbport = (int)$m[1];
if (preg_match("/dbname\s*=\s*'([^']*)'/", $config, $m)) $dbname = $m[1];
if (preg_match("/dbuser\s*=\s*'([^']*)'/", $config, $m)) $dbuser = $m[1];
if (preg_match("/dbpass\s*=\s*'([^']*)'/", $config, $m)) $dbpass = $m[1];

echo "\nDB from config: host=$dbhost port=$dbport dbname=$dbname user=$dbuser\n";

// 5. Database connection
echo "\nTrying database connection... ";
try {
    $dsn = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbuser, $dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "OK (PDO connected).\n";
} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo "  Check: Is MariaDB/MySQL running? Is port $dbport correct?\n";
}

// 6. Dataroot
if (preg_match('/dataroot\s*=\s*[\'"]([^\'"]+)[\'"]/', $config, $m)) {
    $dataroot = str_replace(['\\\\', '\\'], ['\\', '\\'], $m[1]);
    echo "\nDataroot: $dataroot\n";
    echo "  exists: " . (file_exists($dataroot) ? 'yes' : 'NO') . "\n";
    if (file_exists($dataroot)) {
        echo "  writable: " . (is_writable($dataroot) ? 'yes' : 'NO') . "\n";
    } else {
        echo "  Create the folder and make it writable by the web server.\n";
    }
}

// 7. Try loading full config (this is where it often crashes)
echo "\nTrying to load full Moodle config and setup... ";
ob_start();
try {
    define('MOODLE_INTERNAL', true);
    require_once($configpath);
    $out = ob_get_clean();
    if ($out) echo "Output captured: " . trim($out) . "\n";
    echo "OK (config + setup loaded).\n";
} catch (Throwable $e) {
    ob_end_clean();
    echo "FAILED:\n";
    echo "  " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n=== End diagnostic ===\n";
