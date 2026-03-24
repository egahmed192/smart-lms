<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox(
        'block_edumasr_recentcourses/displaycategories',
        get_string('displaycategories', 'block_edumasr_recentcourses'),
        get_string('displaycategories_help', 'block_edumasr_recentcourses'),
        1
    ));

    $settings->add(new admin_setting_configtext(
        'block_edumasr_recentcourses/maxcourses',
        get_string('maxcourses', 'block_edumasr_recentcourses'),
        get_string('maxcourses_help', 'block_edumasr_recentcourses'),
        10,
        PARAM_INT
    ));
}
