<?php
/**
 * Moodle health check - no Moodle bootstrap. Run: php health_check.php
 * Or browser: http://localhost:8080/moodle/scripts/health_check.php
 */
$isCli = (php_sapi_name() === 'cli');
if (!$isCli) header('Content-Type: text/plain; charset=utf-8');

$moodleRoot = dirname(__DIR__, 2);
$publicRoot = dirname(__DIR__);
$configPath = $moodleRoot . DIRECTORY_SEPARATOR . 'config.php';

function parse_config($path) {
    $c = file_get_contents($path);
    $r = ['dbhost' => 'localhost', 'dbport' => 3306, 'dbname' => 'moodle', 'dbuser' => 'root', 'dbpass' => '', 'prefix' => 'mdl_', 'dataroot' => ''];
    if (preg_match("/dbhost\s*=\s*'([^']*)'/", $c, $m)) $r['dbhost'] = $m[1];
    if (preg_match("/dbport['\s]*=>\s*'?(\d+)/", $c, $m)) $r['dbport'] = (int)$m[1];
    if (preg_match("/dbname\s*=\s*'([^']*)'/", $c, $m)) $r['dbname'] = $m[1];
    if (preg_match("/dbuser\s*=\s*'([^']*)'/", $c, $m)) $r['dbuser'] = $m[1];
    if (preg_match("/dbpass\s*=\s*'([^']*)'/", $c, $m)) $r['dbpass'] = $m[1];
    if (preg_match("/prefix\s*=\s*'([^']*)'/", $c, $m)) $r['prefix'] = $m[1];
    if (preg_match('/dataroot\s*=\s*[\'"]([^\'"]+)[\'"]/', $c, $m)) $r['dataroot'] = str_replace(['\\\\','/'], ['\\','\\'], $m[1]);
    return $r;
}

$out = [];
$out[] = "=== Moodle health check " . date('Y-m-d H:i:s') . " ===";
$out[] = "";

if (!file_exists($configPath)) {
    $out[] = "FAIL: config.php not found at $configPath";
    echo implode("\n", $out);
    exit(1);
}

$cfg = parse_config($configPath);
$out[] = "[1] config.php: OK";
$out[] = "[2] DB: host={$cfg['dbhost']} port={$cfg['dbport']} db={$cfg['dbname']}";

try {
    $dsn = "mysql:host={$cfg['dbhost']};port={$cfg['dbport']};dbname={$cfg['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $cfg['dbuser'], $cfg['dbpass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $out[] = "    Connection: OK";
} catch (Throwable $e) {
    $out[] = "    Connection: FAIL - " . $e->getMessage();
    echo implode("\n", $out);
    exit(1);
}

$out[] = "[3] Dataroot: " . $cfg['dataroot'];
$out[] = "    exists=" . (file_exists($cfg['dataroot']) ? "yes" : "no") . " writable=" . (is_writable($cfg['dataroot']) ? "yes" : "no");

$out[] = "[4] DB version:";
try {
    $t = $cfg['prefix'] . 'config';
    $st = $pdo->query("SELECT name, value FROM `{$t}` WHERE name IN ('version','release')");
    if ($st) while ($row = $st->fetch(PDO::FETCH_ASSOC)) $out[] = "    {$row['name']}={$row['value']}";
    if (!$st || $st->rowCount() === 0) $out[] = "    (no rows)";
} catch (Throwable $e) { $out[] = "    " . $e->getMessage(); }

$out[] = "[5] Plugins:";
foreach (['local/odoo_sync','local/parent_portal','local/message_audit','local/assessments','theme/school'] as $p) {
    $path = $publicRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p);
    $out[] = "    $p: " . (is_dir($path) ? "OK" : "MISSING");
}

$out[] = "";
$out[] = "=== End ===";
echo implode("\n", $out);
