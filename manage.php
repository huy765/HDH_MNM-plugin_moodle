<?php
/**
 * @package     local_message
 * @author      Le Quoc Huy (The Jolash)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

global $DB;

require_login();
$context = context_system::instance();
require_capability('local/message:managemessages', $context);

$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('manage_messages', 'local_message'));
$PAGE->set_heading(get_string('manage_messages', 'local_message'));
$PAGE->requires->js_call_amd('local_message/confirm');

$messages = $DB->get_records('local_message', null, 'id');

echo $OUTPUT->header();
$templatecontext = (object)[
    'messages' => array_values($messages),
    'editurl' => new moodle_url('/local/message/edit.php'),
];

echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

echo $OUTPUT->footer();
