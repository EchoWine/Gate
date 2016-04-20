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

	# Login with username: 1 yes, 0 no
	'login_user' => 1,

	# Login with mail: 1 yes, 0 no
	'login_mail' => 1,

	# Display as user -> 0: Username, 1: Mail
	'display' => 0,

	'default.username' => 'admin',
	'default.email' => 'admin@admin.com',
	'default.password' => 'admin',

];

?>