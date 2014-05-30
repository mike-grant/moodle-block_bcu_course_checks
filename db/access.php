<?php
/**
Required file for a Moodle block, in this case Your Course. For reference, see blocks tutorial at:
http://docs.moodle.org/dev/Blocks

*/

$capabilities = array
(

	'block/yourcourse:myaddinstance' => array
	(
		'captype' => 'write',
		'contextlevel' => CONTEXT_SYSTEM,
		'archetypes' => array
		(
			'user' => CAP_ALLOW
		),

		'clonepermissionsfrom' => 'moodle/my:manageblocks'
	),

	'block/yourcourse:addinstance' => array
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