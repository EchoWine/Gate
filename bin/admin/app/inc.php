<?php
	
	$path_lib = '../../../lib';
	$path_config = '../config';

	include $path_lib."/database/main.php";
	include $path_lib."/TemplateEngine/main.php";
	
	DB::connect(include $path_config.'/database.php');

	// Load template
	TemplateEngine::ini('templates');
	TemplateEngine::load('default');

	// Print html page
	$TEMPLATE['head'] = TemplateEngine::loadHTML('head');
	$TEMPLATE['body'] = TemplateEngine::loadHTML('body');
	TemplateEngine::html();

?>