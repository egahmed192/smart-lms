<?php
defined('MOODLE_INTERNAL') || die();

$messageproviders = [
    'offlinepackready' => [
        'capability' => 'moodle/user:editownmessageprofile',
        'defaults' => [
            'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
        ],
    ],
    'digest' => [
        'capability' => 'moodle/user:editownmessageprofile',
        'defaults' => [
            'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
        ],
    ],
];

