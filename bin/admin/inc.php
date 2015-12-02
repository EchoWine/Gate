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
	ModuleManager::load(PATH_MODULE."/Auth");



	// Load template
	TemplateEngine::ini(PATH_TEMPLATES);

	TemplateEngine::load('default');

	Auth::template();

	// Print html page

	# Provvisorio, testing template
	$element = 'Hi';
	$user = array(
		array('name' => 'luca','surname' => 'rossi'),
		array('name' => 'dario','surname' => 'bianchi'),
	);

	include TemplateEngine::html();

	# Provvisorio, debugging
	echo "<script>console.log('Tempo di esecuzione: ".(microtime(true) - $s)."');</script>";

?>