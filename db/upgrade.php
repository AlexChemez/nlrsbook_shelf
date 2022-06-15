<?php

defined('MOODLE_INTERNAL') || die;

function xmldb_block_nlrsbook_shelf_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    return true;
}
