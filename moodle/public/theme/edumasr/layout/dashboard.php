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
 * Dashboard layout for Edumasr Academy - fixed sidebar + custom grid.
 *
 * @package    theme_edumasr
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

global $USER, $CFG;

// On Dashboard (my-index), add blocks to main content region so they appear in the center with other blocks.
// Messages layout uses full-width main content (no blocks column).
$ismessageslayout = ($PAGE->pagelayout === 'messages');
$addblockregion = ($PAGE->pagetype === 'my-index') ? 'content' : null;
$addblockbutton = $ismessageslayout ? '' : $OUTPUT->addblockbutton($addblockregion);
$blockshtml = $ismessageslayout ? '' : $OUTPUT->blocks('side-pre');
$hasblocks = !$ismessageslayout && (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
$courseindex = core_course_drawer();

$bodyattributes = $OUTPUT->body_attributes([
    'edumasr-dashboard-layout',
    'pagelayout-' . $PAGE->pagelayout,
    $ismessageslayout ? 'edumasr-messages-layout' : '',
]);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$logourl = $OUTPUT->get_compact_logo_url();
$templatecontext = [
    'config' => (object)['wwwroot' => $CFG->wwwroot, 'homeurl' => (new moodle_url('/my/'))->out(false)],
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), 'escape' => false]),
    'showlogo' => $OUTPUT->should_display_navbar_logo(),
    'logourl' => $logourl ? $logourl->out(false) : '',
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'sidenavitems' => theme_edumasr_get_sidenav_items(),
    'isloggedin' => isloggedin() && !isguestuser(),
    'fullwidthmain' => $ismessageslayout,
];

echo $OUTPUT->render_from_template('theme_edumasr/dashboard', $templatecontext);
