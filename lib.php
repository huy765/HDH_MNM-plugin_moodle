<?php
/**
 * @package     local_message
 * @author      Le Quoc Huy (The Jolash)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use local_message\manager;

function local_message_before_footer() {
    global $USER;

    if (!get_config('local_message', 'enabled')) {
        return;
    }

    $manager = new manager();
    $messages = $manager->get_messages($USER->id);

    foreach ($messages as $message) {
        $type = \core\output\notification::NOTIFY_INFO;
        if ($message->messagetype === '0') {
            $type = \core\output\notification::NOTIFY_WARNING;
        }
        if ($message->messagetype === '1') {
            $type = \core\output\notification::NOTIFY_SUCCESS;
        }
        if ($message->messagetype === '2') {
            $type = \core\output\notification::NOTIFY_ERROR;
        }
        \core\notification::add($message->messagetext, $type);

        $manager->mark_message_read($message->id, $USER->id);
    }
}
