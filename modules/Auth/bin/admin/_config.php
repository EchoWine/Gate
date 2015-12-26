<?php

return [
	
	'ambiguous' => true,

	'expire' => 60*60*24*3,

	'cookie' => 'session',

	# Login with -> 0: Username, 1: Mail, 2: All
	'user' => 0,

	'credential' => [
		'table' => 'credential',
		'col' => [
			'id' => 'id',
			'user' => 'user',
			'mail' => 'mail',
			'pass' => 'pass',
		],
		'default' => [
			'user' => 'admin',
			'mail' => 'admin',
			'pass' => 'admin',
		]
		
	],

	'session' => [
		'table' => 'session',
		'col' => [
			'sid' => 'sid',
			'uid' => 'uid',
			'expire' => 'expire',
		],
	],

	'data' => [
		'post_user' => 'user',
		'post_pass' => 'pass',
		'post_mail' => 'mail',
		'post_login' => 'login',
		'post_logout' => 'logout',
	]
];

?>