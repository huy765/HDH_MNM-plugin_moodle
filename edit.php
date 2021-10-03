<?php
/**
 * @package     local_message
 * @author      Le Quoc Huy (The Jolash)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_message\form\edit;
use local_message\manager;

require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/message:managemessages', $context);

$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit');

$messageid = optional_param('messageid', null, PARAM_INT);

// hiển thị hình thức  form.
$mform = new edit();

if ($mform->is_cancelled()) {
    // Quay lại trang manage.php 
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('cancelled_form', 'local_message'));

} else if ($fromform = $mform->get_data()) {
    $manager = new manager();

    if ($fromform->id) {
        // updating message.
        $manager->update_message($fromform->id, $fromform->messagetext, $fromform->messagetype);
        redirect($CFG->wwwroot . '/local/message/manage.php', get_string('updated_form', 'local_message') . $fromform->messagetext);
    }

    $manager->create_message($fromform->messagetext, $fromform->messagetype);

    // Quay lại trang manage.php 
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('created_form', 'local_message') . $fromform->messagetext);
}

if ($messageid) {
    // Thêm dữ liệu bổ sung vào form.
    global $DB;
    $manager = new manager();
    $message = $manager->get_message($messageid);
    if (!$message) {
        throw new invalid_parameter_exception('Message not found');
    }
    $mform->set_data($message);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
