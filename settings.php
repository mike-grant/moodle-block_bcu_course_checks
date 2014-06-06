<?php

/**
* Settings for the Your Course block. 
* This enables a "Settings" link in the Blocks administration page:
* Administration >> Site Administration >> Plugins >> Blocks >> Manage Blocks
* ---
* NB: the main block class needs the following method defined:
* function has_config() {return true;}
* in order to enable settings, to tell Moodle that there is a settings.php file. 
* ---
* See blocks tutorial at http://docs.moodle.org/dev/Blocks
* Labels below defined in block language file moodle\blocks\your_course\lang\en\block_your_course.php
* NB: There's naff-all docs about these admin settings. There's an Admin Settings page (http://docs.moodle.org/dev/Admin_settings)
* but for options other than check boxes you have to look in lib/adminlib.php

*/


# Create the block settings form. Firstly, a simple heading...
$settings->add(new admin_setting_heading
	(
		'headerconfig',
		get_string('headerconfig', 'block_your_course'),
		get_string('descconfig', 'block_your_course')
	)
	);
	
# ...then a check box, to enable/disable HTML in the block
 
$settings->add(new admin_setting_configcheckbox
	(
		'simplehtml/Allow_HTML',
		get_string('labelallowhtml', 'block_your_course'),
		get_string('descallowhtml', 'block_your_course'),
		'1'
	)
	);
		
?>