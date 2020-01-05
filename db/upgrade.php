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
 * This file keeps track of upgrades to the qpractice module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    mod_qpractice
 * @copyright  2019 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute qpractice upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_qpractice_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2019031900) {

        // Define field topcategory to be added to qpractice.
        $table = new xmldb_table('qpractice');
        $field = new xmldb_field('topcategory', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'intro');

        // Conditionally launch add field topcategory.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Qpractice savepoint reached.
        upgrade_mod_savepoint(true, 2019031900, 'qpractice');
    }
    if ($oldversion < 2020010504) {

        if (!$dbman->table_exists('qpractice_categories')) {
            $table = new xmldb_table('qpractice_categories');
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
            $table->add_field('qpracticeid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');
            $table->add_field('categoryid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'qpracticeid');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $dbman->create_table($table);
        }
        // Qpractice savepoint reached.
        upgrade_mod_savepoint(true, 2020010504, 'qpractice');
    }

    return true;
}

