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
        global $COURSE, $DB;


        if($records = $DB->get_records("block_exaquestcategories",  array("coursecategoryid" => $COURSE->category))){

            $namesets = array(array(null),array(null),array(null),array(null));

            foreach($records as $record){
                $namesets[$record->categorytype][] = $record->categoryname;
            }

        }


        $select = $mform->addElement('select', 'fragencharakter', get_string('Fragencharakter'), $namesets[0]);
        $select->setMultiple(false);
        $mform->addRule('fragencharakter', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('fragencharakter', 'message text', 'nonzero', null, 'client');
        $select = $mform->addElement('select', 'klassifikation', get_string('Klassifikation'), $namesets[1]);
        $select->setMultiple(false);
        $mform->addRule('klassifikation', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('klassifikation', 'message text', 'nonzero', null, 'client');
        $select = $mform->addElement('select', 'fragefach', get_string('Fragefach'), $namesets[2]);
        $select->setMultiple(false);
        $mform->addRule('fragefach', get_string('missingcolor'), 'required', null, 'client');
        $mform->addRule('fragefach', 'message text', 'nonzero', null, 'client');
        $select = $mform->addElement('select', 'lehrinhalt', get_string('Lehrinhalt'), $namesets[3]);
        $select->setMultiple(true);
        $mform->addRule('lehrinhalt', get_string('missingcolor'), 'required', null, 'client');
        //$mform->addRule('colorssss', 'message text', 'nonzero', null, 'client');
    }

    /**
     * Returns the default value as it would be stored in the database (not in human-readable format).
     *
     * @return mixed
     */
    public function get_default_value() {
        global $DB;
        $DB->insert_record('block_exaquestquestcat_mm', array("questionid" => 1, "exaquestcategoryid" => 2));

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
