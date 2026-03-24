<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\mod_forum\event\discussion_created',
        'callback' => '\local_studentlife\observer::forum_discussion_created',
    ],
    [
        'eventname' => '\mod_assign\event\submission_graded',
        'callback' => '\local_studentlife\observer::assign_submission_graded',
    ],
    [
        'eventname' => '\mod_quiz\event\quiz_grade_updated',
        'callback' => '\local_studentlife\observer::quiz_grade_updated',
    ],
];

