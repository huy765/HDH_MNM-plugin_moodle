<?php
/**
 * @package    local_message
 * @category   phpunit
 */

defined('MOODLE_INTERNAL') || die();

use local_message\manager;
global $CFG;
require_once($CFG->dirroot . '/local/message/lib.php');

class local_message_manager_test extends advanced_testcase
{
    /**
     * Kiểm tra việc có thể tạo một tin nhắn.
     */
    public function test_create_message() {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manager();
        $messages = $manager->get_messages(2);
        $this->assertEmpty($messages);

        $type = \core\output\notification::NOTIFY_SUCCESS;
        $result = $manager->create_message('Test message', $type);

        $this->assertTrue($result);
        $messages = $manager->get_messages(2);
        $this->assertNotEmpty($messages);

        $this->assertCount(1, $messages);
        $message = array_pop($messages);

        $this->assertEquals('Test message', $message->messagetext);
        $this->assertEquals($type, $message->messagetype);
    }

    /**
     * Kiểm tra để đảm bảo rằng nhận được các thông báo chính xác.
     */
    public function test_get_messages() {

        global $DB;
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manager();

        $type = \core\output\notification::NOTIFY_SUCCESS;
        $manager->create_message('Test message1', $type);
        $manager->create_message('Test message2', $type);
        $manager->create_message('Test message3', $type);

        $messages = $DB->get_records('local_message');

        foreach ($messages as $id => $message) {
            $manager->mark_message_read($id, 1);
        }

        $messagesAdmin = $manager->get_messages(2);
        $this->assertCount(3, $messagesAdmin);

        foreach ($messages as $id => $message) {
            $manager->mark_message_read($id, 2);
        }

        $messagesAdmin = $manager->get_messages(2);
        $this->assertCount(0, $messagesAdmin);
    }
}
