<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

$THEME->name = 'school';
$THEME->parents = ['boost'];
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->usefallback = true;
$THEME->scss = function($theme) {
    return theme_school_get_main_scss_content($theme);
};
// Use same layout definitions as boost (files resolved via parent theme).
$THEME->layouts = [
    'base' => ['file' => 'drawers.php', 'regions' => []],
    'standard' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'course' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre', 'options' => ['langmenu' => true]],
    'coursecategory' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'incourse' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'frontpage' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre', 'options' => ['nonavbar' => true]],
    'admin' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'mydashboard' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre', 'options' => ['nonavbar' => true, 'langmenu' => true]],
    'mypublic' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'login' => ['file' => 'login.php', 'regions' => [], 'options' => ['langmenu' => true]],
    'popup' => ['file' => 'columns1.php', 'regions' => [], 'options' => ['nofooter' => true, 'nonavbar' => true]],
    'frametop' => ['file' => 'columns1.php', 'regions' => [], 'options' => ['nofooter' => true, 'nocoursefooter' => true]],
    'embedded' => ['file' => 'embedded.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'maintenance' => ['file' => 'maintenance.php', 'regions' => []],
    'print' => ['file' => 'columns1.php', 'regions' => [], 'options' => ['nofooter' => true, 'nonavbar' => false]],
    'redirect' => ['file' => 'embedded.php', 'regions' => []],
    'report' => ['file' => 'drawers.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
    'secure' => ['file' => 'secure.php', 'regions' => ['side-pre'], 'defaultregion' => 'side-pre'],
];
$THEME->enable_dock = false;
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->activityheaderconfig = ['notitle' => true];
