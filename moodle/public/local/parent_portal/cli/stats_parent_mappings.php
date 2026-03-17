<?php
// CLI helper: show counts of parent/child relationships.
define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../config.php');

global $DB;

// Total active relationships.
$totalrels = $DB->count_records('local_parent_portal_rel', ['active' => 1]);

// Distinct parents that have at least one active child link.
$parentsql = "SELECT COUNT(DISTINCT parent_userid)
                FROM {local_parent_portal_rel}
               WHERE active = 1";
$parentcount = (int)$DB->get_field_sql($parentsql);

// Distinct students that have at least one active parent link.
$studentsql = "SELECT COUNT(DISTINCT student_userid)
                 FROM {local_parent_portal_rel}
                WHERE active = 1";
$studentcount = (int)$DB->get_field_sql($studentsql);

echo "Active parent-child relationships: {$totalrels}\n";
echo "Distinct parent accounts with at least one child: {$parentcount}\n";
echo "Distinct student accounts with at least one parent: {$studentcount}\n";

exit(0);

