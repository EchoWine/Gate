<?php

	$dir = dirname(__FILE__);

	$AuthModel = new AuthModel();

	$AuthController = new AuthController($AuthModel,include $dir."/_config.php");
	$AuthController -> check();

	$AuthView = new AuthView($AuthModel,$AuthController);


	# Definition of some variables

	$p = dirname(__FILE__);

	$AuthView -> forceLogin($p);


	$pathModuleAuth = ModuleManager::getPath()."/Auth/bin/admin/templates/".TemplateEngine::getName()."/";

	$logged = false;


	$auth = (object)[
		'title' => 'Sign in',
		'mail' => (object)[
			'name' => 'mail',
			'label' => 'Email address',
			'value' => '',
		],
		'pass' => (object)[
			'name' => $AuthController -> getNameData('pass'),
			'label' => 'Password',
			'value' => $AuthController -> getValueData('pass'),
		],
		'remember' => (object)[
			'name' => 'remember_me',
			'label' => 'Remember me'
		],
		'login' => (object)[
			'name' => 'login',
			'label' => 'Sign in'
		],

	];
?>