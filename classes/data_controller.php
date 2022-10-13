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
 * Customfield Checkbox plugin
 *
 * @package   customfield_checkbox
 * @copyright 2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace customfield_exaquestcategory;

use core_customfield\api;
use core_customfield\output\field_data;

defined('MOODLE_INTERNAL') || die;

/**
 * Class data
 *
 * @package customfield_checkbox
 * @copyright 2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class data_controller extends \core_customfield\data_controller {

    /**
     * Return the name of the field where the information is stored
     * @return string
     */
    public function datafield() : string {
        return 'intvalue';
    }

    /**
     * Add fields for editing a checkbox field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function instance_form_definition(\MoodleQuickForm $mform) {
        global $COURSE;

        $select = $mform->addElement('select', 'colors', get_string('Fragencharakter'), array(null, 'red', 'blue', 'green'));
        $select->setMultiple(false);
        $mform->addRule('colors', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('colors', 'message text', 'nonzero', null, 'client');
        $select = $mform->addElement('select', 'colorss', get_string('Klassifikation'), array(null, 'red', 'blue', 'green'));
        $select->setMultiple(false);
        $mform->addRule('colorss', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('colorss', 'message text', 'nonzero', null, 'client');
        $select = $mform->addElement('select', 'colorsss', get_string('Fragefach'), array(null, 'red', 'blue', 'green'));
        $select->setMultiple(false);
        $mform->addRule('colorsss', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('colorsss', 'message text', 'nonzero', null, 'client');
        $select = $mform->addElement('select', 'colorssss', get_string('Lehrinhalt'), array(null, 'red', 'blue', 'green'));
        $select->setMultiple(true);
        $mform->addRule('colorssss', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('colorssss', 'message text', 'nonzero', null, 'client');
    }

    /**
     * Returns the default value as it would be stored in the database (not in human-readable format).
     *
     * @return mixed
     */
    public function get_default_value() {
        return $this->get_field()->get_configdata_property('checkbydefault') ? 1 : 0;
    }

    /**
     * Returns value in a human-readable format
     *
     * @return mixed|null value or null if empty
     */
    public function export_value() {
        $value = $this->get_value();
        return $value ? get_string('yes') : get_string('no');
    }
}
