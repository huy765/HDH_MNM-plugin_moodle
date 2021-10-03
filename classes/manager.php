<?php
/**
 * @package     local_message
 * @author      Le Quoc Huy (The Jolash)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message;

use dml_exception;
use stdClass;

class manager {

    /** Chèn dữ liệu vào bảng
     * @param string $message_text
     * @param string $message_type
     * @return bool true if successful
     */
    public function create_message(string $message_text, string $message_type): bool
    {
        global $DB;
        $record_to_insert = new stdClass();
        $record_to_insert->messagetext = $message_text;
        $record_to_insert->messagetype = $message_type;
        try {
            return $DB->insert_record('local_message', $record_to_insert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Nhận tất cả các tin nhắn chưa đọc bởi người dùng 
     * @param int $userid người dùng mà đang nhận được tin nhắn
     * @return array of messages
     */
    public function get_messages(int $userid): array
    {
        global $DB;
        $sql = "SELECT lm.id, lm.messagetext, lm.messagetype 
            FROM {local_message} lm 
            LEFT OUTER JOIN {local_message_read} lmr ON lm.id = lmr.messageid AND lmr.userid = :userid 
            WHERE lmr.userid IS NULL";
        $params = [
            'userid' => $userid,
        ];
        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            // Log error here.
            return [];
        }
    }

    /** Đánh dấu rằng một tin nhắn đã được đọc bởi người dùng này.
     * @param int $message_id đánh dấu tin nhắn là đã đọc
     * @param int $userid người dùng mà đánh dấu tin nhắn đã đọc
     * @return bool true if successful
     */
    public function mark_message_read(int $message_id, int $userid): bool
    {
        global $DB;
        $read_record = new stdClass();
        $read_record->messageid = $message_id;
        $read_record->userid = $userid;
        $read_record->timeread = time();
        try {
            return $DB->insert_record('local_message_read', $read_record, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Nhận một tin nhắn từ id 
     * @param int $messageid thông điệp đang cố gắng đạt được.
     * @return object|false message thông báo hoặc sai nếu không tìm thấy.
     */
    public function get_message(int $messageid)
    {
        global $DB;
        return $DB->get_record('local_message', ['id' => $messageid]);
    }

    /** Cập nhật chi tiết cho một tin nhắn.
     * @param int $messageid thông điệp đang cố gắng đạt được.
     * @param string $message_text the new text for the message.
     * @param string $message_type the new type for the message.
     * @return bool message thông báo hoặc sai nếu không tìm thấy.
     */
    public function update_message(int $messageid, string $message_text, string $message_type): bool
    {
        global $DB;
        $object = new stdClass();
        $object->id = $messageid;
        $object->messagetext = $message_text;
        $object->messagetype = $message_type;
        return $DB->update_record('local_message', $object);
    }

    /** Xóa tin nhắn và tất cả lịch sử đã đọc.
     * @param $messageid
     * @return bool
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function delete_message($messageid)
    {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        $deletedMessage = $DB->delete_records('local_message', ['id' => $messageid]);
        $deletedRead = $DB->delete_records('local_message_read', ['messageid' => $messageid]);
        if ($deletedMessage && $deletedRead) {
            $DB->commit_delegated_transaction($transaction);
        }
        return true;
    }
}
