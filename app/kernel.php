<?php
		
	# Error reporting
	error_reporting(-1);
	ini_set('display_errors', 'On');
	ini_set("log_errors", 1);
	ini_set("error_log", __DIR__."/php-error.log");

	# Path
	define('PATH',__DIR__.'/../public');
	define('PATH_APP','../app');
	define('PATH_SRC','../src');
	define('PATH_LIB','../lib');
	define('PATH_CONFIG','../config');

	include "loader.php";
	include "helpers.php";


	$apps = [
		new CoreWine\Exceptions\Application(),
		new CoreWine\Requirements\Application(),
		new CoreWine\TemplateEngine\Application(),
		new CoreWine\SourceManager\Application(),
		new CoreWine\DataBase\Application(),
		new CoreWine\ORM\Application(),
		new CoreWine\Application(),
	];

	foreach($apps as $app){
		$app -> app();
	}

	

?>