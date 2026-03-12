<?php
defined('MOODLE_INTERNAL') || die();
$messageproviders = [
    'license_expiry_reminder' => [
        'defaults' => [
            'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
        ],
    ],
];
