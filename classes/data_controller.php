<?php
/**
 * Select plugin data controller
 *
 * @package   customfield_select
 * @copyright 2018 Daniel Neis Araujo <daniel@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace customfield_exaquestcategory;

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
        return 'intvalue';
    }

    /**
     * Returns the default value as it would be stored in the database (not in human-readable format).
     *
     * @return mixed
     */
    public function get_default_value()
    {
        $defaultvalue = $this->get_field()->get_configdata_property('defaultvalue');
        if ('' . $defaultvalue !== '') {
            $key = array_search($defaultvalue, $this->nameset);
            if ($key !== false) {
                return $key;
            }
        }
        return 0;
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


        if($records = $DB->get_records("block_exaquestcategories",  array("coursecategoryid" => $COURSE->category))){

            $namesets = array(array(null),array(null),array(null),array(null));

            foreach($records as $record){
                $namesets[$record->categorytype][] = $record->categoryname;
            }
            $this->nameset = $namesets[$categorytype];

            $elementname = $this->get_form_element_name();

            switch($categorytype){
                case 0:
                    $select = $mform->addElement('select', $elementname, $field->get_formatted_name(), $namesets[0]);
                    $select->setMultiple(false);
                    $mform->addRule($elementname, get_string('missingcolor'), 'required', null, 'client');
                    $mform->addRule($elementname, 'message text', 'nonzero', null, 'client');
                    break;
                case 1:
                    $select = $mform->addElement('select', $elementname, $field->get_formatted_name(), $namesets[1]);
                    $select->setMultiple(false);
                    $mform->addRule($elementname, get_string('missingcolor'), 'required', null, 'client');
                    $mform->addRule($elementname, 'message text', 'nonzero', null, 'client');
                    break;
                case 2:
                    $select = $mform->addElement('select', $elementname, $field->get_formatted_name(), $namesets[2]);
                    $select->setMultiple(false);
                    $mform->addRule($elementname, get_string('missingcolor'), 'required', null, 'client');
                   $mform->addRule($elementname, 'message text', 'nonzero', null, 'client');
                    break;
                case 3:
                    $select = $mform->addElement('select', $elementname, $field->get_formatted_name(), $namesets[3]);
                    $select->setMultiple(false);
                    $mform->addRule($elementname, get_string('missingcolor'), 'required', null, 'client');
                    break;
            }

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
        return $errors;
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

