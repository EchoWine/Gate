<?php
		
	# Path
	define('PATH',dirname(__FILE__));
	define('PATH_MODULE','../../modules');
	define('PATH_LIB','../../lib');
	define('PATH_CONFIG','config');
	define('PATH_TEMPLATES','templates');

	$s = microtime(true);

	include PATH_LIB."/database/main.php";
	include PATH_LIB."/TemplateEngine/main.php";
	include PATH_LIB."/ModuleManager/main.php";
	
	DB::connect(include PATH_CONFIG.'/database.php');


	# modules

	# Load all modules
	# ModuleManager::loadAll(PATH_MODULE);

	# Load manually a module
	ModuleManager::setPath(PATH_MODULE);
	ModuleManager::load(PATH_MODULE."/Auth");


	// Load template
	TemplateEngine::ini(PATH_TEMPLATES);

	TemplateEngine::load('lte');

	foreach(ModuleManager::loadTemplate('admin') as $k)
		include $k;


	// Print html page

	# Provvisorio, testing template
	$element = 'Hi';
	$user = array(
		array('name' => 'luca','surname' => 'rossi'),
		array('name' => 'dario','surname' => 'bianchi'),
	);


	TemplateEngine::compile();

?>