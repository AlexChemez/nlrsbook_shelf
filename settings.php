<?php

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtextarea('nlrsbook_shelf/org_private_key', get_string('org_private_key', 'block_nlrsbook_shelf'), "", null));
}