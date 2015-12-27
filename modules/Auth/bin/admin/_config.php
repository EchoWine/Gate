<?php

return [
	
	'ambiguous' => true,

	# Name of cookie
	'cookie' => 'session',

	'normal' => [
		'expire' => 60*60*24,


		# 0: Cookie, 1: Session
		'data' => 1,
	],
	'remember' => [
		'expire' => 60*60*24*7*30,

		# 0: Cookie, 1: Session
		'data' => 0,
	],

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
		'post_remember' => 'remember',
		'post_logout' => 'logout',
	]
];

?>