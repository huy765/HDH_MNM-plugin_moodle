<?php

/**
 * local_message external file
 *
 * @package    component
 * @category   external
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use local_message\manager;
require_once($CFG->libdir . "/externallib.php");

class local_message_external extends external_api  {
    /**
     * Trả về mô tả các tham số phương thức
     * @return external_function_parameters
     */
    public static function delete_message_parameters() {
        return new external_function_parameters(
            ['messageid' => new external_value(PARAM_INT, 'id of message')],
        );
    }

    /**
     * Các chức năng chính nó
     * @return string welcome message
     */
    public static function delete_message($messageid): string {
        $params = self::validate_parameters(self::delete_message_parameters(), array('messageid'=>$messageid));

        require_capability('local/message:managemessages', context_system::instance());

        $manager = new manager();
        return $manager->delete_message($messageid);
    }

    /**
     * Trả về mô tả giá trị kết quả của phương thức
     * @return external_description
     */
    public static function delete_message_returns() {
        return new external_value(PARAM_BOOL, 'Đúng nếu tin nhắn đã được xóa thành công.');
    }
}
