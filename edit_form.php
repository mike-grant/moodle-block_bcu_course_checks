<?php

/**
Used in Your Course block to provide extra configuration options for the block. 
Accessed via the 'cog' in the plugin header, in editing mode, option "Configure <block name> block".
See Blocks tutorial at:
http://docs.moodle.org/dev/Blocks
*/
 
class block_bcu_your_course_edit_form extends block_edit_form 
{
 
	/**
	* Define form fields.
	* See Moodle Form API at http://docs.moodle.org/dev/Form_API
	*/
 
    protected function specific_definition($mform) 
	{ 
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        
        $mform->addElement('date_selector', 'config_displayuntil', get_string('displayuntil', 'block_bcu_your_course'), array(
            'startyear' => date('Y'),
            'optional'  => false
        ));
        
		# Add a text area for comments on the module
		$mform->addElement('textarea', 'config_topmodulenotes', get_string("topmodulenotes", "block_bcu_your_course"), 'wrap="virtual" rows="4" cols="50"');
        $mform->setType('config_topmodulenotes', PARAM_TEXT); 
        
        $mform->addElement('textarea', 'config_bottommodulenotes', get_string('bottommodulenotes', 'block_bcu_your_course'), 'wrap="virtual" rows="4" cols="50"');
        $mform->setType('config_bottommodulenotes', PARAM_TEXT);
    }
}


?>