<?php
		

	include "loader.php";
	include "helpers.php";


	$apps = [
		new CoreWine\Exceptions\ExceptionsApp(),
		new CoreWine\Requirements\RequirementsApp(),
		new CoreWine\TemplateEngine\TemplateEngineApp(),
		new CoreWine\SourceManager\SourceManagerApp(),


		new CoreWine\DataBase\DataBaseApp(),
		new CoreWine\FrameworkApp(),
		

	];

	foreach($apps as $app){
		$app -> app();
	}




?>