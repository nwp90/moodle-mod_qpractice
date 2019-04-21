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
 * Question practice module test data generator class
 *
 * @package mod_qpractice
 * @copyright 2019 Marcus Green
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Question practice module test data generator class
 *
 * @package mod_qpractice
 * @copyright 2016 Marcus Green
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_qpractice_generator extends testing_module_generator {

    /**
     * create an instance
     *
     * @param array $record
     * @param array $options
     * @return stdClass
     */
    public function create_instance(array $record = null, array $options = null) : stdClass {
        global $CFG;
        require_once($CFG->dirroot.'/mod/qpractice/locallib.php');
        $record = (object)(array)$record;
        $defaultquizsettings = array(
            'questionusageid'         => 0,
            'name'                    => 'QpracticeTest',
            'intro      '             => '',
            'introformat'             => 1,
            'behaviour'               => array('interactive'),
             );
        foreach ($defaultquizsettings as $name => $value) {
            if (!isset($record->{$name})) {
                $record->{$name} = $value;
            }
        }
        return parent::create_instance($record, (array)$options);
    }
}
