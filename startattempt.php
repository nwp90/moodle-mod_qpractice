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
 * This script deals with starting a new attempt for a qpractice.
 *
 * It will end up redirecting to attempt.php.
 *
 * @package    mod_qpractice
 * @copyright  2013 Jayesh Anandani
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/startattempt_form.php');
require_once($CFG->libdir . '/questionlib.php');

$id = required_param('id', PARAM_INT); // Course_module ID.


$PAGE->set_url('/mod/qpractice/startattempt.php', array('id' => $id));
$DB->set_field('qpractice_session', 'status', 'finished', null);

if ($id) {
    if (!$cm = get_coursemodule_from_id('qpractice', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }
    $qpractice = $DB->get_record('qpractice', array('id' => $cm->instance));
    $qpractice_categories = $DB->get_records('qpractice_categories', ['qpracticeid' => $cm->id]);
}

require_login($course, true, $cm);
global $PAGE;

$context = context_module::instance($cm->id);
$coursecontext = $context->get_course_context();
/** mavg */
$categories = qpractice_get_question_categories($coursecontext, $qpractice->topcategory);

$behaviours = get_options_behaviour($cm);

$data = array();
$data['categories'] = $categories;
$data['behaviours'] = $behaviours;
$data['instanceid'] = $cm->instance;

$mform = new mod_qpractice_startattempt_form(null, $data);

if ($mform->is_cancelled()) {
    $returnurl = new moodle_url('/mod/qpractice/view.php', array('id' => $cm->id));
    redirect($returnurl);
} else if ($fromform = $mform->get_data()) {

    $sessionid = qpractice_session_create($fromform, $context);
    $nexturl = new moodle_url('/mod/qpractice/attempt.php', array('id' => $sessionid));
    redirect($nexturl);
}

$mform->set_data(array(
    'id' => $cm->id,
));

// Print the page header.
$PAGE->set_title(format_string($qpractice->name));
$PAGE->set_heading(format_string($qpractice->name));
$PAGE->set_context($context);

// Output starts here.
echo $OUTPUT->header();

$mform->display();

// Finish the page.
echo $OUTPUT->footer();
