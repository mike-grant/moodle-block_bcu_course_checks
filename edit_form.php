<?php

/**
Used in Your Course block to provide extra configuration options for the block. 
Accessed via the 'cog' in the plugin header, in editing mode, option "Configure <block name> block".
See Blocks tutorial at:
http://docs.moodle.org/dev/Blocks
*/
 
class block_your_course_edit_form extends block_edit_form 
{
 
	/**
	* Define form fields.
	* See Moodle Form API at http://docs.moodle.org/dev/Form_API
	*/
 
    protected function specific_definition($mform) 
	{
 
        // Section header title according to language file.
		# Language file is /moodle/lang/en/block.php
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
		
		# Option to change the block title
		$mform->addElement('text', 'config_title', get_string('blocktitle', 'block_your_course'));
		$mform->setDefault('config_title', 'default value');
		$mform->setType('config_title', PARAM_MULTILANG);	
		
		# Options to show/hide block elements (assignments, leader photo, etc)	
		# NB: a plain 'checkbox' can't be used - you have to use 'advcheckbox' for settings to be saved. 
		# See http://docs.moodle.org/dev/lib/formslib.php_Form_Definition#checkbox
		
		# Add a check box to toggle module leader photo display
		$mform->addElement('advcheckbox', 'config_leaderphoto', get_string('leaderphoto', 'block_your_course'));	
		
		# Add a check box to toggle display of assignment links
		$mform->addElement('advcheckbox', 'config_assignments', get_string('assignments', 'block_your_course'));			
 
		# Add a text area for comments on the module
		$mform->addElement('textarea', 'config_modulenotes', get_string("modulenotes", "block_your_course"), 'wrap="virtual" rows="20" cols="50"');
        $mform->setType('config_modulenotes', PARAM_TEXT); 
		
 
    } 
 

}


?>