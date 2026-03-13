<?php
/**
 * List local/theme plugins and their installed version from DB.
 * Run: php plugin_registry_check.php
 */
$isCli = (php_sapi_name() === 'cli');
if (!$isCli) header('Content-Type: text/plain; charset=utf-8');

$moodleRoot = dirname(__DIR__, 2);
$configPath = $moodleRoot . DIRECTORY_SEPARATOR . 'config.php';
if (!file_exists($configPath)) { echo "No config\n"; exit(1); }

$c = file_get_contents($configPath);
$port = 3306;
if (preg_match("/dbport['\s]*=>\s*'?(\d+)/", $c, $m)) $port = (int)$m[1];
preg_match("/dbhost\s*=\s*'([^']*)'/", $c, $m); $host = $m[1] ?? 'localhost';
preg_match("/dbname\s*=\s*'([^']*)'/", $c, $m); $dbname = $m[1] ?? 'moodle';
preg_match("/dbuser\s*=\s*'([^']*)'/", $c, $m); $user = $m[1] ?? 'root';
preg_match("/dbpass\s*=\s*'([^']*)'/", $c, $m); $pass = $m[1] ?? '';
preg_match("/prefix\s*=\s*'([^']*)'/", $c, $m); $prefix = $m[1] ?? 'mdl_';

$out = [];
$out[] = "=== Plugin registry " . date('Y-m-d H:i:s') . " ===";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $t = $prefix . 'config_plugins';
    $want = ['local_odoo_sync', 'local_parent_portal', 'local_message_audit', 'local_assessments', 'theme_school'];
    $placeholders = implode(',', array_fill(0, count($want), '?'));
    $st = $pdo->prepare("SELECT plugin, name, value FROM `{$t}` WHERE plugin IN ($placeholders) AND name = 'version'");
    $st->execute($want);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach ($want as $w) {
        $v = null;
        foreach ($rows as $r) { if ($r['plugin'] === $w) { $v = $r['value']; break; } }
        $out[] = "  $w: " . ($v !== null ? "installed ($v)" : "NOT in DB");
    }
} catch (Throwable $e) {
    $out[] = "Error: " . $e->getMessage();
}

$out[] = "=== End ===";
echo implode("\n", $out);
