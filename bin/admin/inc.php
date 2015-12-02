<?php
	
	$s = microtime(true);

	$path_lib = '../../lib';
	$path_config = 'config';

	include $path_lib."/database/main.php";
	include $path_lib."/TemplateEngine/main.php";
	
	DB::connect(include $path_config.'/database.php');

	// Load template
	TemplateEngine::ini('templates');
	TemplateEngine::load('default');

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