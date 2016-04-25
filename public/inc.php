<?php
	

	use CoreWine\DataBase\DB as DB;
	use CoreWine\Request as Request;
	use CoreWine\Route as Route;
	use CoreWine\SourceManager\Manager;
	use CoreWine\TemplateEngine\Engine;

	# Path
	define('PATH',dirname(__FILE__));
	define('PATH_APP','../app');
	define('PATH_SRC','../src');
	define('PATH_LIB','../lib');
	define('PATH_STORAGE','../storage');
	define('PATH_CONFIG','../config');

	include "helpers.php";

	DB::connect(include PATH_CONFIG.'/database.php');
	# Load all sources
	
	Manager::loadAll(PATH_SRC);
	
	Manager::loaded();


	# Alias
	class_alias('CoreWine\Request', 'Request');
	class_alias('CoreWine\Route', 'Route');
	class_alias('CoreWine\Flash', 'Flash');
	class_alias('CoreWine\TemplateEngine\Engine', 'Engine');
	class_alias('Auth\Service\Auth', 'Auth');
	class_alias('SystemInfo\Service\SystemInfo', 'SystemInfo');

	# Load template
	Engine::ini(PATH_STORAGE);

	define('path',Request::getDirUrl());


	# Compile
	Engine::compile(PATH_APP,'Resources/views');

	
	foreach(Manager::$list as $name => $dir){
		Engine::compile(
			PATH_APP,
			"Resources/".$name."/views",
			$name
		);
	}

	foreach(Manager::$list as $name => $dir){
		Engine::compile(
			PATH_SRC,
			$name."/Resources/views",
			$name
		);
	}

	Engine::translates();

?>