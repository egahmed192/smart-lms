<?php
/**
 * Check if Moodle upgrade is needed (compare version.php vs DB config).
 * Run: php upgrade_status.php
 */
$isCli = (php_sapi_name() === 'cli');
if (!$isCli) header('Content-Type: text/plain; charset=utf-8');

$moodleRoot = dirname(__DIR__, 2);
$publicRoot = dirname(__DIR__);
$configPath = $moodleRoot . DIRECTORY_SEPARATOR . 'config.php';
$versionPath = $publicRoot . DIRECTORY_SEPARATOR . 'version.php';

$out = [];
$out[] = "=== Upgrade status " . date('Y-m-d H:i:s') . " ===";

if (!file_exists($configPath) || !file_exists($versionPath)) {
    $out[] = "Missing config or version.php";
    echo implode("\n", $out);
    exit(1);
}

// Parse config (db only)
$c = file_get_contents($configPath);
$port = 3306;
if (preg_match("/dbport['\s]*=>\s*'?(\d+)/", $c, $m)) $port = (int)$m[1];
preg_match("/dbhost\s*=\s*'([^']*)'/", $c, $m); $host = $m[1] ?? 'localhost';
preg_match("/dbname\s*=\s*'([^']*)'/", $c, $m); $dbname = $m[1] ?? 'moodle';
preg_match("/dbuser\s*=\s*'([^']*)'/", $c, $m); $user = $m[1] ?? 'root';
preg_match("/dbpass\s*=\s*'([^']*)'/", $c, $m); $pass = $m[1] ?? '';
preg_match("/prefix\s*=\s*'([^']*)'/", $c, $m); $prefix = $m[1] ?? 'mdl_';

$vContent = file_get_contents($versionPath);
$version = null;
$release = null;
if (preg_match('/\$version\s*=\s*([\d.]+)/', $vContent, $m)) $version = $m[1];
if (preg_match('/\$release\s*=\s*[\'"]([^\'"]+)[\'"]/', $vContent, $m)) $release = $m[1];

$out[] = "Code: version=$version release=" . ($release ?? '?');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $t = $prefix . 'config';
    $st = $pdo->query("SELECT value FROM `{$t}` WHERE name = 'version'");
    $dbVersion = $st ? $st->fetchColumn() : null;
    $out[] = "DB:   version=" . ($dbVersion ?? 'null');
    if ($dbVersion !== null && $version !== null) {
        $needUpgrade = (float)$version > (float)$dbVersion;
        $out[] = "Upgrade needed: " . ($needUpgrade ? "YES" : "no");
    }
} catch (Throwable $e) {
    $out[] = "DB error: " . $e->getMessage();
}

$out[] = "=== End ===";
echo implode("\n", $out);
