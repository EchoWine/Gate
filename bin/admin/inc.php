<?php

	$path_lib = '../../lib';
	$path_config = 'config';

	include $path_lib."/database/main.php";
	include $path_lib."/TemplateEngine/main.php";
	
	DB::connect(include $path_config.'/database.php');

	// Load template
	TemplateEngine::ini('templates');
	TemplateEngine::load('default');

	// Print html page

	$element = 'Hi';
	/*
	$tmpl['element'] = '';
	$tmpl['head'] = TemplateEngine::loadHTML('head');
	$tmpl['body'] = TemplateEngine::loadHTML('body');
	*/
	include TemplateEngine::html();

?>