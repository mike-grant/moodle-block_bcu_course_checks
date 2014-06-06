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
 
        // A sample string variable with a default value.
		# This should get the string 'blockstring' from 
		# blocks/your_course/lang/en/block/block_your_course.php but 
		# you need to purge caches to see it work if you edit the string.

        //$mform->addElement('text', 'config_text', get_string('option1', 'block_simplehtml'));
		//$mform->setDefault('config_text', 'default value');
        //$mform->setType('config_text', PARAM_TEXT);  
		# Add a text area
		$mform->addElement('textarea', 'config_textarea', get_string("field_text", "block_your_course"), 'wrap="virtual" rows="20" cols="50"');
		$mform->setDefault('config_textarea', 'default textarea value');
        $mform->setType('config_textarea', PARAM_TEXT); 
		
		// A sample string variable with a default value.
		# These values should be acted upon by the specialization() method in 
		# the main block class.
		$mform->addElement('text', 'config_title', get_string('blocktitle', 'block_your_course'));
		$mform->setDefault('config_title', 'this and that');
		$mform->setType('config_title', PARAM_MULTILANG);
	
		# Add various other elements for the hell of it
		# See http://docs.moodle.org/dev/lib/formslib.php_Form_Definition for element list
		# Date picker
		//$mform->addElement('date_selector', 'assesstimefinish', get_string('to'));
       
 
    } 
 

}


?>