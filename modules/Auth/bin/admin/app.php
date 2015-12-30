<?php

	$dir = dirname(__FILE__);

	$Model = new AuthModel();

	$Controller = new AuthController($Model,include $dir."/_config.php");
	$Controller -> check();
	$Response = $Controller -> response;

	$View = new AuthView($Model,$Controller);


	# Definition of some variables

	$View -> setLogin();
	$View -> setHeader();

	$pathModuleAuth = ModuleManager::getPath()."/Auth/bin/admin/templates/".TemplateEngine::getName()."/";



	$auth = (object)[
		'title' => 'Sign in',
		'user' => $Controller -> getData('user'),
		'pass' => $Controller -> getData('pass'),
		'login' => $Controller -> getData('login'),
		'logout' => $Controller -> getData('logout'),
		'remember' => $Controller -> getData('remember'),
		'response' => $Response,
		'display' => $Controller -> getUserDisplay(),

	];
?>