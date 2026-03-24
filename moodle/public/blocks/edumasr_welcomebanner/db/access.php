<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License.
// See the GNU General Public License for more details.
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'block/edumasr_welcomebanner:myaddinstance' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => ['user' => CAP_ALLOW],
        'clonepermissionsfrom' => 'moodle/my:manageblocks',
    ],
];

