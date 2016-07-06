<?php
		
	# Error reporting
	error_reporting(-1);
	ini_set('display_errors', 'On');

	# Path
	define('PATH',__DIR__.'/../public');
	define('PATH_APP','../app');
	define('PATH_SRC','../src');
	define('PATH_LIB','../lib');
	define('PATH_CONFIG','../config');

	include "loader.php";
	include "helpers.php";


	$apps = [
		new CoreWine\Exceptions\ExceptionsApp(),
		new CoreWine\Requirements\RequirementsApp(),
		new CoreWine\TemplateEngine\TemplateEngineApp(),
		new CoreWine\SourceManager\SourceManagerApp(),
		new CoreWine\DataBase\DataBaseApp(),
		new CoreWine\ORM\ORMApp(),
		new CoreWine\FrameworkApp(),
	];

	foreach($apps as $app){
		$app -> app();
	}




?>