<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_description(
        'block_edumasr_progressoverview/progress_help',
        '',
        get_string('progress_help', 'block_edumasr_progressoverview')
    ));

    $settings->add(new admin_setting_configcheckbox(
        'block_edumasr_progressoverview/displaycategories',
        get_string('displaycategories', 'block_edumasr_progressoverview'),
        get_string('displaycategories_help', 'block_edumasr_progressoverview'),
        0
    ));

    $settings->add(new admin_setting_configtext(
        'block_edumasr_progressoverview/maxcourses',
        get_string('maxcourses', 'block_edumasr_progressoverview'),
        get_string('maxcourses_help', 'block_edumasr_progressoverview'),
        10,
        PARAM_INT
    ));
}

