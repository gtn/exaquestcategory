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
 * Class field
 *
 * @package   customfield_select
 * @copyright 2018 David Matamoros <davidmc@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace customfield_exaquestcategory;

defined('MOODLE_INTERNAL') || die;

/**
 * Class field
 *
 * @package customfield_select
 * @copyright 2018 David Matamoros <davidmc@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class field_controller extends \core_customfield\field_controller {
    /**
     * Customfield type
     */
    const TYPE = 'exaquestcategory';

    /**
     * Add fields for editing a select field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function config_form_definition(\MoodleQuickForm $mform) {
        $mform->addElement('header', 'header_specificsettings', get_string('specificsettings', 'customfield_select'));
        $mform->setExpanded('header_specificsettings', true);
        $mform->addElement('select', 'configdata[categorytype]', get_string('Kategorientyp'), array(0 => 'Fragencharakter', 1 => 'Klassifikation', 2 => 'Fragefach', 3 => 'Lehrinhalt'));
        $mform->setType('configdata[categorytype]', PARAM_INT);

    }

    /**
     * Return configured field options
     *
     * @return array
     */
    public function get_categorytype() {
        return intval($this->get_configdata_property('categorytype'));
    }

    /**
     * Validate the data from the config form.
     * Sub classes must reimplement it.
     *
     * @param array $data from the add/edit profile field form
     * @param array $files
     * @return array associative array of error messages
     */
    public function config_form_validation(array $data, $files = array()) : array {
        $errors = parent::config_form_validation($data, $files);

        if ($data['configdata']['uniquevalues']) {
            $errors['configdata[uniquevalues]'] = get_string('errorconfigunique', 'customfield_checkbox');
        }

        return $errors;
    }

    /**
     * Return configured field options
     *
     * @return array
     */
    public function get_options(): array {
        global $DB, $COURSE;

        $records = $DB->get_records("block_exaquestcategories",  array("coursecategoryid" => $COURSE->category));

        $namesets = array(array(null),array(null),array(null));

        foreach($records as $record){
            //$recordnamearray = preg_split("/\n/", $record->categoryname);#
            //array_pop($recordnamearray);
            //array_pop($recordnamearray);
            $namesets[$record->categorytype][] = $record->categoryname;
            //array_unshift($namesets[$record->categorytype]," ");

        }
        $nameset = $namesets[$this->get_categorytype()];
        If(nameset == null){
            return array("leer");
        }



        return $nameset;
    }

    /**
     * Does this custom field type support being used as part of the block_myoverview
     * custom field grouping?
     * @return bool
     */
    public function supports_course_grouping(): bool {
        return true;
    }

    /**
     * If this field supports course grouping, then this function needs overriding to
     * return the formatted values for this.
     * @param array $values the used values that need formatting
     * @return array
     */
    public function course_grouping_format_values($values): array {
        $options = $this->get_categorytype();
        $ret = [];
        foreach ($values as $value) {
            if (isset($options[$value])) {
                $ret[$value] = format_string($options[$value]);
            }
        }
        $ret[BLOCK_MYOVERVIEW_CUSTOMFIELD_EMPTY] = get_string('nocustomvalue', 'block_myoverview',
            $this->get_formatted_name());
        return $ret;
    }

    /**
     * Locate the value parameter in the field options array, and return it's index
     *
     * @param string $value
     * @return int
     */
    public function parse_value(string $value) {
        return (int) array_search($value, $this->get_categorytype());
    }

}