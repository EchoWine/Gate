<?php

	$dir = dirname(__FILE__);

	$Model = new AuthModel();

	$Controller = new AuthController($Model,include $dir."/_config.php");
	$Response = $Controller -> check();

	$View = new AuthView($Model,$Controller);


	# Definition of some variables

	$p = dirname(__FILE__).'/templates';

	$View -> forceLogin($p);
	$View -> setHeader($p);


	$pathModuleAuth = ModuleManager::getPath()."/Auth/bin/admin/templates/".TemplateEngine::getName()."/";

	$logged = false;



	$auth = (object)[
		'title' => 'Sign in',
		'user' => $Controller -> getData('user'),
		'pass' => $Controller -> getData('pass'),
		'login' => $Controller -> getData('login'),
		'logout' => $Controller -> getData('logout'),
		'remember' => (object)[
			'name' => 'remember_me',
			'label' => 'Remember me'
		],

		'response' => $Response

	];
?>