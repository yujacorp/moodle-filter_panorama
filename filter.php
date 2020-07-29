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
 * @package    filter_panorama
 * @author     Darren Mei
 * @copyright  Copyright (c) 2020 YuJa Inc. (https://www.yuja.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

class filter_panorama extends moodle_text_filter
{
    public function __construct($context, array $localconfig) {
        parent::__construct($context, $localconfig);
    }

    public function setup($page, $context) {
        global $CFG, $USER, $COURSE, $PAGE;
        static $js_initialized = false;

        if ($js_initialized) {
            return;
        }

        $courseContext = context_course::instance($COURSE->id);
        $enabledCourses = explode(',', $CFG->courses);
        $key = '';
        $injectReports = false;
        $config = get_config('panorama');

        if (has_capability('moodle/course:managefiles', $courseContext, $USER->id) && in_array($COURSE->id, $enabledCourses)) {
            $key = $config->key1;
            $injectReports = true;
        } else {
            $key = $config->key2;
        }

        $serverUrl = 'UNKNOWN';
        switch ($config->environment) {
            case "Local":
                $serverUrl = "https://localhost:444";
                break;
            case "Staging":
                $serverUrl = "https://staging-panorama-api.yuja.com";
                break;
            case "Production":
                $serverUrl = "https://panorama-api.yuja.com";
                break;
        }

        $PAGE->requires->js_call_amd('filter_panorama/panorama', 'init', [$serverUrl, $key, $injectReports]);
        $js_initialized = true;
    }

    public function filter($text, array $options = [])
    {
        return $text;
    }
}
