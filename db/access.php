<?php
/**
Required file for a Moodle block, in this case Your Course. For reference, see blocks tutorial at:
http://docs.moodle.org/dev/Blocks

*/

$capabilities = array
(

	'block/bcu_your_course:myaddinstance' => array
	(
		'captype' => 'write',
		'contextlevel' => CONTEXT_SYSTEM,
		'archetypes' => array
		(
			'user' => CAP_ALLOW
		),

		'clonepermissionsfrom' => 'moodle/my:manageblocks'
	),

	'block/bcu_your_course:addinstance' => array
	(
		'riskbitmask' => RISK_SPAM | RISK_XSS,

		'captype' => 'write',
		'contextlevel' => CONTEXT_BLOCK,
		'archetypes' => array
		(
			'editingteacher' => CAP_ALLOW,
			'manager' => CAP_ALLOW
		),

		'clonepermissionsfrom' => 'moodle/site:manageblocks'
	),
);

?>