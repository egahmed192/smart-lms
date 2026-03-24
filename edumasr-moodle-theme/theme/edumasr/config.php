<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Edumasr Academy theme config.
 *
 * @package    theme_edumasr
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

$THEME->name = 'edumasr';
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->editor_scss = ['editor'];
$THEME->usefallback = true;
$THEME->scss = function($theme) {
    return theme_edumasr_get_main_scss_content($theme);
};

$THEME->parents = ['boost'];
$THEME->enable_dock = false;
$THEME->extrascsscallback = 'theme_edumasr_get_extra_scss';
$THEME->prescsscallback = 'theme_edumasr_get_pre_scss';
$THEME->precompiledcsscallback = 'theme_boost_get_precompiled_css';
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->activityheaderconfig = ['notitle' => true];

// Use custom dashboard layout for my dashboard page.
$THEME->layouts = [
    'base' => array('file' => 'drawers.php', 'regions' => array()),
    'standard' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'course' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre', 'options' => array('langmenu' => true)),
    'coursecategory' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'incourse' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'frontpage' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre', 'options' => array('nonavbar' => true)),
    'admin' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'mycourses' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre', 'options' => array('nonavbar' => true)),
    'mydashboard' => array('file' => 'dashboard.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre', 'options' => array('nonavbar' => true, 'langmenu' => true)),
    'messages' => array('file' => 'dashboard.php', 'regions' => array(), 'options' => array('nonavbar' => true, 'langmenu' => true)),
    'mypublic' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'login' => array('file' => 'login.php', 'regions' => array(), 'options' => array('langmenu' => true)),
    'popup' => array('file' => 'columns1.php', 'regions' => array(), 'options' => array('nofooter' => true, 'nonavbar' => true, 'activityheader' => array('notitle' => true, 'nocompletion' => true, 'nodescription' => true))),
    'frametop' => array('file' => 'columns1.php', 'regions' => array(), 'options' => array('nofooter' => true, 'nocoursefooter' => true, 'activityheader' => array('nocompletion' => true))),
    'embedded' => array('file' => 'embedded.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'maintenance' => array('file' => 'maintenance.php', 'regions' => array()),
    'print' => array('file' => 'columns1.php', 'regions' => array(), 'options' => array('nofooter' => true, 'nonavbar' => false, 'noactivityheader' => true)),
    'redirect' => array('file' => 'embedded.php', 'regions' => array()),
    'report' => array('file' => 'drawers.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre'),
    'secure' => array('file' => 'secure.php', 'regions' => array('side-pre'), 'defaultregion' => 'side-pre', 'options' => array('activityheader' => array('notitle' => false))),
];
