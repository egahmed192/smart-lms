<?php
// Simple CLI helper: list some parent->student mappings from local_parent_portal_rel.
define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');

global $DB;

$sql = "SELECT r.id,
               p.id AS parentid,
               p.username AS parentusername,
               s.id AS studentid,
               s.username AS studentusername
          FROM {local_parent_portal_rel} r
          JOIN {user} p ON p.id = r.parent_userid
          JOIN {user} s ON s.id = r.student_userid
         WHERE r.active = 1
      ORDER BY r.id ASC";

$records = $DB->get_records_sql($sql, null, 0, 20);

if (!$records) {
    echo "No active parent/student mappings found.\n";
    exit(0);
}

foreach ($records as $r) {
    echo "rel {$r->id}: parent {$r->parentusername} (id {$r->parentid})"
       . " -> student {$r->studentusername} (id {$r->studentid})\n";
}

exit(0);

