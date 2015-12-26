<?php

	$dir = dirname(__FILE__);

	$Model = new AuthModel();

	$Controller = new AuthController($Model,include $dir."/_config.php");
	$Controller -> check();

	$View = new AuthView($Model,$Controller);


	# Definition of some variables

	$p = dirname(__FILE__).'/templates';

	$View -> forceLogin($p);
	$View -> setHeader($p);


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
			'name' => $Controller -> getNameData('pass'),
			'label' => $Controller -> getLabelData('pass'),
			'value' => $Controller -> getValueData('pass'),
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