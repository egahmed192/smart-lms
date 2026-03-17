<?php
define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->dirroot . '/user/lib.php');

[$options] = cli_get_params(
    ['input' => null, 'limit' => 20, 'help' => false],
    ['h' => 'help']
);

if (!empty($options['help'])) {
    echo "Verify CSV credentials against Moodle auth.\n";
    echo "  --input=PATH   CSV path\n";
    echo "  --limit=N      Number of rows to check (default 20)\n";
    exit(0);
}

$csv = $options['input'] ?: ($CFG->dirroot . '/local/odoo_sync/cli/odoo_lms_credentials.enriched.csv');
$limit = max(1, (int)$options['limit']);

if (!file_exists($csv)) {
    cli_error('CSV not found: ' . $csv);
}

$fh = fopen($csv, 'r');
if ($fh === false) {
    cli_error('Cannot open CSV: ' . $csv);
}

$header = fgetcsv($fh);
if (!$header) {
    fclose($fh);
    cli_error('Empty CSV.');
}

$idxuser = array_search('moodle_username', $header, true);
$idxpass = array_search('plain_password', $header, true);
$idxmail = array_search('moodle_email', $header, true);

if ($idxuser === false || $idxpass === false) {
    fclose($fh);
    cli_error('CSV must include moodle_username and plain_password columns.');
}

$checked = 0;
$ok = 0;
$failed = 0;

while (($row = fgetcsv($fh)) !== false && $checked < $limit) {
    $username = (string)($row[$idxuser] ?? '');
    $password = (string)($row[$idxpass] ?? '');
    $email = $idxmail !== false ? (string)($row[$idxmail] ?? '') : '';
    if ($username === '' || $password === '') {
        continue;
    }

    $checked++;
    $user = authenticate_user_login($username, $password, false, $reason, false);
    if ($user) {
        $ok++;
        echo "[OK] {$username}";
        if ($email !== '') {
            echo " ({$email})";
        }
        echo "\n";
    } else {
        $failed++;
        $reasontext = isset($reason) ? (string)$reason : 'unknown';
        $dbuser = $DB->get_record('user', ['username' => $username, 'deleted' => 0], '*', IGNORE_MISSING);
        if ($dbuser) {
            $internalok = validate_internal_user_password($dbuser, $password) ? 'yes' : 'no';
            $islocal = ((int)$dbuser->mnethostid === (int)$CFG->mnet_localhost_id) ? 'yes' : 'no';
            echo "[FAIL] {$username} reason={$reasontext} auth={$dbuser->auth} suspended={$dbuser->suspended} confirmed={$dbuser->confirmed} mnethostid={$dbuser->mnethostid} localhostid={$CFG->mnet_localhost_id} islocal={$islocal} internalpwd={$internalok}\n";
        } else {
            echo "[FAIL] {$username} reason={$reasontext} dbuser=missing\n";
        }
    }
}

fclose($fh);
echo "Checked={$checked} OK={$ok} FAIL={$failed}\n";

