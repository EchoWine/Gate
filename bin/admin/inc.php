<?php
		
	# Path
	define('PATH',dirname(__FILE__));
	define('PATH_MODULE','../../modules');
	define('PATH_LIB','../../lib');
	define('PATH_CONFIG','config');
	define('PATH_TEMPLATES','templates');

	$s = microtime(true);


	include PATH_LIB."/main.fun.php";
	include PATH_LIB."/stdObject.class.php";
	
	include PATH_LIB."/stdData/main.php";
	
	include PATH_LIB."/database/main.php";
	include PATH_LIB."/TemplateEngine/main.php";
	include PATH_LIB."/ModuleManager/main.php";
	include PATH_LIB."/Data/main.php";
	
	DB::connect(include PATH_CONFIG.'/database.php');


	# modules

	# Load all modules
	ModuleManager::loadAll(PATH_MODULE);

	# Load manually a module
	#ModuleManager::setPath(PATH_MODULE);
	#ModuleManager::load(PATH_MODULE."/Auth");

	# Load template
	TemplateEngine::ini(PATH_TEMPLATES);

	TemplateEngine::load('default');

	# Navigation 
	$nav = [];
	/*$nav = array(
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
		include $k;
		TemplateEngine::compile(
			dirname($k)."/templates/".TemplateEngine::getName()."/"
		);
	}


	# Compile
	TemplateEngine::compile();

?>