<?php
/**
 * Quick diagnostic: show task_scheduled count and first 5 classnames.
 * Open in browser: http://localhost:8080/moodle/admin/check_task_count.php
 * Delete after use.
 */
require_once(__DIR__ . '/../config.php');
require_admin();
global $DB;
$count = $DB->count_records('task_scheduled');
$sample = $DB->get_records('task_scheduled', null, 'id', 'id,classname,component', 0, 5);
header('Content-Type: text/plain; charset=utf-8');
echo "task_scheduled count: {$count}\n\nFirst 5 rows:\n";
foreach ($sample as $r) {
    echo "  {$r->id} | {$r->component} | {$r->classname}\n";
}
