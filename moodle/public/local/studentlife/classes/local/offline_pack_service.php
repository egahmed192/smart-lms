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

namespace local_studentlife\local;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

final class offline_pack_service {
    public const FILEAREA_OFFLINEPACK = 'offlinepack';

    /**
     * Get the existing offline pack file (if any).
     *
     * @param int $userid
     * @param int $courseid
     * @return \stored_file|null
     */
    public static function get_existing_pack(int $userid, int $courseid): ?\stored_file {
        $context = \context_user::instance($userid);
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'local_studentlife', self::FILEAREA_OFFLINEPACK, $courseid, 'timemodified DESC', false);
        return $files ? reset($files) : null;
    }

    /**
     * Build and store an offline pack in the user's context.
     *
     * @param int $userid
     * @param int $courseid
     * @param bool $includeindex
     * @return \stored_file
     */
    public static function build_and_store_pack(int $userid, int $courseid, bool $includeindex = true): \stored_file {
        global $CFG;

        $course = get_course($courseid);
        $context = \context_user::instance($userid);

        // Collect files from resource/folder activities.
        $collected = self::collect_course_files($course);

        $tempdir = make_temp_directory('local_studentlife/offlinepack/' . uniqid('', true));
        $filesmap = [];
        $indexlinks = [];

        foreach ($collected as $item) {
            /** @var \stored_file $file */
            $file = $item['file'];
            $zipname = $item['zipname'];
            $dest = $tempdir . '/' . $zipname;
            check_dir_exists(dirname($dest));
            $file->copy_content_to($dest);
            $filesmap[$zipname] = $dest;
            if ($includeindex) {
                $indexlinks[] = ['name' => $zipname, 'href' => rawurlencode($zipname)];
            }
        }

        if ($includeindex) {
            $indexhtml = self::render_index_html($course, $indexlinks);
            $indexpath = $tempdir . '/' . get_string('offlinepack:indexfilename', 'local_studentlife');
            file_put_contents($indexpath, $indexhtml);
            $filesmap[get_string('offlinepack:indexfilename', 'local_studentlife')] = $indexpath;
        }

        $zipfile = $tempdir . '/' . clean_filename($course->shortname . '_offline_pack.zip');
        $zippacker = get_file_packer('application/zip');
        $zippacker->archive_to_pathname($filesmap, $zipfile);

        $existing = self::get_existing_pack($userid, $courseid);
        if ($existing) {
            $existing->delete();
        }

        $fs = get_file_storage();
        $filerecord = [
            'contextid' => $context->id,
            'component' => 'local_studentlife',
            'filearea' => self::FILEAREA_OFFLINEPACK,
            'itemid' => $courseid,
            'filepath' => '/',
            'filename' => basename($zipfile),
        ];

        $stored = $fs->create_file_from_pathname($filerecord, $zipfile);

        // Cleanup temp.
        remove_dir($tempdir);

        return $stored;
    }

    /**
     * Collect stored_files from course modules and generate zip paths with clear naming.
     *
     * @param \stdClass $course
     * @return array[] Each item: ['file' => stored_file, 'zipname' => string]
     */
    private static function collect_course_files(\stdClass $course): array {
        $modinfo = get_fast_modinfo($course);
        $context = \context_course::instance($course->id);
        $fs = get_file_storage();

        $items = [];
        foreach ($modinfo->get_cms() as $cm) {
            if (!$cm->uservisible) {
                continue;
            }
            if (!in_array($cm->modname, ['resource', 'folder'], true)) {
                continue;
            }

            $component = 'mod_' . $cm->modname;
            $filearea = 'content';
            $instanceid = $cm->instance;

            $files = $fs->get_area_files($context->id, $component, $filearea, $instanceid, 'filepath, filename', false);
            if (!$files) {
                continue;
            }

            $sectionnum = property_exists($cm, 'sectionnum') ? (int)$cm->sectionnum : 0;
            $prefix = clean_filename($course->shortname . '_Section' . str_pad((string)$sectionnum, 2, '0', STR_PAD_LEFT) . '_' .
                clean_filename($cm->get_formatted_name()));

            foreach ($files as $file) {
                /** @var \stored_file $file */
                if ($file->is_directory()) {
                    continue;
                }
                $filename = clean_filename($file->get_filename());
                $zipname = $prefix . '_' . $filename;
                // Keep it in a single folder to make offline browsing simpler.
                $zipname = 'resources/' . $zipname;

                $items[] = [
                    'file' => $file,
                    'zipname' => $zipname,
                ];
            }
        }

        return $items;
    }

    /**
     * Render a simple offline index.html with links to packed files.
     *
     * @param \stdClass $course
     * @param array[] $links Each item: ['name' => string, 'href' => string]
     * @return string
     */
    private static function render_index_html(\stdClass $course, array $links): string {
        $title = htmlspecialchars(format_string($course->fullname), ENT_QUOTES);
        $items = '';
        foreach ($links as $l) {
            $name = htmlspecialchars($l['name'], ENT_QUOTES);
            $href = htmlspecialchars($l['href'], ENT_QUOTES);
            $items .= "<li><a href=\"{$href}\">{$name}</a></li>\n";
        }
        return "<!doctype html>\n<html><head><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n" .
            "<title>{$title} – Offline pack</title></head><body>\n<h1>{$title}</h1>\n<ul>\n{$items}</ul>\n</body></html>";
    }
}

