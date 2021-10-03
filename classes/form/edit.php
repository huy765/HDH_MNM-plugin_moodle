<?php
/**
 * @package     local_message
 * @author      Le Quoc Huy (The Jolash)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message\form;
use moodleform;

require_once("$CFG->libdir/formslib.php");

class edit extends moodleform {
    //Thêm các phần tử vào biểu mẫu
    public function definition() {
        global $CFG;
        $mform = $this->_form; 

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('text', 'messagetext', get_string('message_text', 'local_message')); // Thêm các phần tử vào form
        $mform->setType('messagetext', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('messagetext', get_string('enter_message', 'local_message'));        //Default value

        $choices = array();
        $choices['0'] = \core\output\notification::NOTIFY_WARNING;
        $choices['1'] = \core\output\notification::NOTIFY_SUCCESS;
        $choices['2'] = \core\output\notification::NOTIFY_ERROR;
        $choices['3'] = \core\output\notification::NOTIFY_INFO;
        $mform->addElement('select', 'messagetype', get_string('message_type', 'local_message'), $choices);
        $mform->setDefault('messagetype', '3');

        $this->add_action_buttons();
    }
    //Xác thực tùy chỉnh nên được thêm vào
    function validation($data, $files) {
        return array();
    }
}
