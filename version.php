<?php

/**
Version information for the Your Course plugin.
For reference, see blocks tutorial at 
http://docs.moodle.org/dev/Blocks
*/
# NB: $plugin is a Moodle-defined variable, and must be used
# Version of the plugin itself - I've inserted the datetime of writing this file
# ie 3pm May 9th 2014

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2014091505;  // YYYYMMDDHH (year, month, day, 24-hr time)
$plugin->requires = 2014051200; // YYYYMMDDHH (This is the release version for Moodle 2.0)
$plugin->component = 'block_bcu_your_course'; // Full name of the plugin (used for diagnostics)