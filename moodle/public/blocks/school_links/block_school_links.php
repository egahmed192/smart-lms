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
// along with Moodle. If not, see <http://www.moodle.org/license>.

defined('MOODLE_INTERNAL') || die();

/**
 * School quick links block: quick access to Parent portal, Message audit, Odoo sync, Assessments, etc.
 */
class block_school_links extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_school_links');
    }

    public function get_content() {
        global $CFG;
        if (isset($this->content)) {
            return $this->content;
        }
        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = $this->build_quick_links();
        return $this->content;
    }

    /**
     * Build list of quick links for newly introduced features (per requirements).
     * Each link is shown only if the user has the required capability.
     *
     * @return string HTML
     */
    protected function build_quick_links() {
        $context = context_system::instance();
        $items = [];

        // Parent portal (parent–student relationships).
        if (has_capability('local/parent_portal:manage_relationships', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/parent_portal/index.php'),
                get_string('pluginname', 'local_parent_portal'),
                'fa-users'
            );
        }

        // Message audit (monitoring teacher–parent–student messaging).
        if (has_capability('local/message_audit:view_logs', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/message_audit/index.php'),
                get_string('pluginname', 'local_message_audit'),
                'fa-message'
            );
        }

        // Send bulk message.
        if (has_capability('local/message_audit:send_bulk_message', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/message_audit/bulk.php'),
                get_string('bulk_message', 'local_message_audit'),
                'fa-paper-plane'
            );
        }

        // Keyword rules (flag/restrict messages).
        if (has_capability('local/message_audit:view_logs', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/message_audit/keywords.php'),
                get_string('keyword_rules', 'local_message_audit'),
                'fa-key'
            );
        }

        // Odoo sync status (integration & data sync).
        if (has_capability('local/odoo_sync:manage', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/odoo_sync/status.php'),
                get_string('sync_status', 'local_odoo_sync'),
                'fa-arrows-rotate'
            );
        }

        // Class/Course mapping (Odoo–Moodle course mapping).
        if (has_capability('local/odoo_sync:manage', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/odoo_sync/course_map.php'),
                get_string('course_map', 'local_odoo_sync'),
                'fa-map'
            );
        }

        // Odoo sync settings (license sync, etc.).
        if (has_capability('local/odoo_sync:manage', $context) && has_capability('moodle/site:config', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/admin/settings.php', ['section' => 'local_odoo_sync']),
                get_string('odoo_sync_settings', 'local_odoo_sync'),
                'fa-gear'
            );
        }

        // Export secret codes (grades/assessments – designated users).
        if (has_capability('local/assessments:view_secret_codes', $context)) {
            $items[] = $this->make_link(
                new moodle_url('/local/assessments/export_codes.php'),
                get_string('export_secret_codes', 'local_assessments'),
                'fa-file-export'
            );
        }

        if (empty($items)) {
            return '';
        }
        return html_writer::tag('div', implode('', $items), ['class' => 'list-group']);
    }

    /**
     * @param moodle_url $url
     * @param string $label
     * @param string $icon Font Awesome icon class (e.g. fa-message)
     * @return string
     */
    protected function make_link(moodle_url $url, $label, $icon = 'fa-link') {
        $iconhtml = html_writer::tag('i', '', ['class' => 'icon fa ' . $icon . ' fa-fw', 'aria-hidden' => 'true']);
        return html_writer::link($url, $iconhtml . ' ' . s($label), ['class' => 'list-group-item list-group-item-action']);
    }

    public function applicable_formats() {
        return ['all' => true];
    }
}
