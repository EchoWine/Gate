<?php
		
	# Path
	define('PATH',dirname(__FILE__));
	define('PATH_MODULE','../../modules');
	define('PATH_LIB','../../lib');
	define('PATH_CONFIG','config');
	define('PATH_TEMPLATES','templates');

	$s = microtime(true);


	include PATH_LIB."/dependence.class.php";
	dependence::ini();

	include PATH_LIB."/main.fun.php";
	include PATH_LIB."/stdObject.class.php";
	include PATH_LIB."/stdResponse.class.php";
	include PATH_LIB."/http.class.php";
	
	include PATH_LIB."/stdData/main.php";

	include PATH_LIB."/database/main.php";
	include PATH_LIB."/TemplateEngine/main.php";
	include PATH_LIB."/ModuleManager/main.php";
	include PATH_LIB."/Data/main.php";
	
	DB::connect(include PATH_CONFIG.'/database.php');


	# Ini http
	http::ini();

	# Ini Pages
	define('PAGE','p');
	$pageValue = isset($_GET[PAGE]) ? $_GET[PAGE] : '';

	# modules


	# Load primary module
	ModuleManager::setPath(PATH_MODULE);
	ModuleManager::load(PATH_MODULE."/Item");
	ModuleManager::load(PATH_MODULE."/Auth");
	ModuleManager::load(PATH_MODULE."/Credential");
	ModuleManager::load(PATH_MODULE."/Session");

	# Load all modules
	// ModuleManager::loadAll(PATH_MODULE);

	# Load template
	TemplateEngine::ini(PATH_TEMPLATES);

	TemplateEngine::load('default');

	# Navigation 
	$nav = [];
	/* $nav = array(
		array(
			'name' => 'dashboard',
			'label' => 'Dashboard',
			'url' => 'index.php',
			'icon' => 'home'
		),
	);

	ksort($nav);
	*/


	define('path','templates/'.TemplateEngine::getName().'/');


	# Include template page of modules
	foreach(ModuleManager::loadTemplate('admin') as $k){
		include $k -> app;
		TemplateEngine::compile(
			dirname($k -> app)."/templates/".TemplateEngine::getName()."/",
			TemplateEngine::parseSubClass($k -> name)
		);
	}


	# Compile
	TemplateEngine::compile();

?>