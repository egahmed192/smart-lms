<?php
define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

global $DB, $CFG;

$localhostid = (int)$CFG->mnet_localhost_id;

$sql = "SELECT DISTINCT u.id
          FROM {user} u
          JOIN {local_odoo_sync_map} m ON m.userid = u.id
         WHERE u.deleted = 0
           AND u.mnethostid <> :localhostid";

$ids = $DB->get_fieldset_sql($sql, ['localhostid' => $localhostid]);

if (empty($ids)) {
    cli_writeln('No Odoo-mapped users need mnethostid fix.');
    exit(0);
}

[$insql, $params] = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED);
$params['localhostid'] = $localhostid;

$updatesql = "UPDATE {user}
                 SET mnethostid = :localhostid
               WHERE id {$insql}";
$DB->execute($updatesql, $params);

cli_writeln('Updated mnethostid for ' . count($ids) . ' Odoo-mapped users.');
cli_writeln('Set to local host id: ' . $localhostid);

