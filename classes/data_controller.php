<?php
/**
 * Select plugin data controller
 *
 * @package   customfield_select
 * @copyright 2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace customfield_exaquestcategory;

use core_customfield\data;

defined('MOODLE_INTERNAL') || die;

/**
 * Class data
 *
 * @package customfield_select
 * @copyright 2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class data_controller extends \core_customfield\data_controller
{

    /**
     * Return the name of the field where the information is stored
     * @return string
     */
    public function datafield(): string
    {
        return 'value';
    }

    public function get_default_value() {
        $defaultvalue = $this->get_field()->get_configdata_property('defaultvalue');
        $options = $this->get_field()->get_options();
        $defaultvaluesarray = [];
        $values = explode(",", $defaultvalue);

        foreach ($values as $val) {
            $index = $this->get_option_index($val, $options);
            if ($index !== false) {
                $defaultvaluesarray[] = intval($index);
            }
        }
        return $defaultvaluesarray;
    }

    /**
     * Get the option index in the array of options from the raw text value
     *
     * @param mixed $rawvalue
     * @param array $options
     * @return false|int|string
     */
    protected function get_option_index($rawvalue, $options) {
        return array_search($rawvalue, $options);
    }

    /**
     * Add fields for editing a textarea field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function instance_form_definition(\MoodleQuickForm $mform)
    {
        global $COURSE, $DB;

        $field = $this->get_field();
        $categorytype= $field->get_categorytype();

        $nameset = $field->get_options();
        $elementname = $this->get_form_element_name();

        if($categorytype == 3) {
            //$select = $mform->addElement('select', $elementname, $field->get_formatted_name(), $nameset);
            //$select->setMultiple(true);

            $options = array(
                'multiple' => true,
            );
            $mform->addElement('autocomplete', $elementname, $field->get_formatted_name(), $nameset, $options);
            $mform->addRule($elementname, '', 'required', null, 'client');

        } else {
            $select = $mform->addElement('select', $elementname, $field->get_formatted_name(), $nameset);
            $select->setMultiple(false);
            $mform->addRule($elementname, '', 'required', null, 'client');
            $mform->addRule($elementname, get_string('required_messagetext', 'customfield_exaquestcategory'), 'nonzero', null, 'client');
        }
    }

    /**
     * Validates data for this field.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function instance_form_validation(array $data, array $files): array
    {
        $errors = parent::instance_form_validation($data, $files);
        if ($this->get_field()->get_configdata_property('required')) {
            // Standard required rule does not work on select element.
            $elementname = $this->get_form_element_name();
            if (empty($data[$elementname])) {
                $errors[$elementname] = get_string('err_required', 'form');
            }
        }
        return [];
    }

    public function instance_form_before_set_data(\stdClass $instance) {
        if($this->get_field()->get_categorytype() == 3){

            $instance->{$this->get_form_element_name()} = implode(',', $this->get_value());
        } else {
            parent::instance_form_before_set_data($instance);
        }
    }


    /**
     * Saves the data coming from form
     *
     * @param \stdClass $datanew data coming from the form
     * @throws \coding_exception
     */
    public function instance_form_save(\stdClass $datanew) {
        if($this->get_field()->get_categorytype() == 3) {
            $elementname = $this->get_form_element_name();
            if (!property_exists($datanew, $elementname)) {
                return;
            }
            $value = implode(',', $datanew->$elementname);
            $this->data->set($this->datafield(), $value);
            $this->data->set('value', $value);
            $this->save();
        }else{
            parent::instance_form_save($datanew);
        }
    }

    /**
     * Returns the value as it is stored in the database or default value if data record is not present
     *
     * @return array
     */
    public function get_value() {
        if($this->get_field()->get_categorytype() == 3) {
            if (!$this->get('id')) {
                return $this->get_default_value();
            }
            return explode(',', $this->get($this->datafield()));
        } else {
            return parent::get_value();
        }
    }

    /**
     * Set the value as it should be stored in the database
     *
     * @param array $value to be set and transformed into a comma separated string
     * @return data
     */
    public function set_value($value) {
        if($this->get_field()->get_categorytype() == 3) {
            return $this->set($this->datafield(), implode(',', $value));
        } else {
            return parent::set_value();
        }
    }


    /**
     * Returns value in a human-readable format
     *
     * @return mixed|null value or null if empty
     */
    public function export_value()
    {
        $value = $this->get_value();

        if ($this->is_empty($value)) {
            return null;
        }

        $options = $this->nameset;
        if (array_key_exists($value, $options)) {
            return format_string($options[$value], true,
                ['context' => $this->get_field()->get_handler()->get_configuration_context()]);
        }

        return null;
    }
}

