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

require_once(__DIR__ . '/../../config.php');

require_login();
require_sesskey();

$returnurl = optional_param('returnurl', '', PARAM_URL);
if (!$returnurl) {
    $returnurl = (new moodle_url('/my/'))->out(false);
}

$pref = (int)get_user_preferences('theme_school_lowbandwidth', 0, $USER);
$new = $pref ? 0 : 1;
set_user_preference('theme_school_lowbandwidth', $new);

redirect($returnurl);

