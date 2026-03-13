<?php
/**
 * Run all checks and output combined report. Run: php run_all.php
 */
$scripts = ['health_check.php', 'upgrade_status.php', 'plugin_registry_check.php'];
$base = __DIR__ . DIRECTORY_SEPARATOR;

foreach ($scripts as $s) {
    $path = $base . $s;
    if (!is_file($path)) continue;
    echo "\n--- $s ---\n";
    passthru('php ' . escapeshellarg($path));
}
echo "\n--- Done ---\n";
