<?php
	
	use CoreWine\DataBase\DB as DB;
	use CoreWine\Request as Request;
	use CoreWine\Router;
	use CoreWine\SourceManager\Manager;
	use CoreWine\TemplateEngine\Engine;

	# Path
	define('PATH_BASE','/gate-cms');
	define('PATH',__DIR__);
	define('PATH_APP','../app');
	define('PATH_SRC','../src');
	define('PATH_LIB','../lib');
	define('PATH_PUBLIC','');
	define('PATH_STORAGE','../storage');
	define('PATH_CONFIG','../config');

	include "loader.php";
	include "helpers.php";
	
	new \CoreWine\Exceptions\Handler('ExceptionsController','render');

	# Load template
	Engine::ini(dirname(__FILE__)."/".PATH_STORAGE);

	DB::connect(include PATH_CONFIG.'/database.php');
	
	# Load all sources
	Manager::loadAll(PATH_SRC);
		
	Manager::callControllersRoutes();
	Router::setRequest();
	Manager::callControllersChecks();

	# Alias
	class_alias('CoreWine\Request', 'Request');
	class_alias('CoreWine\Router', 'Router');
	class_alias('CoreWine\Flash', 'Flash');
	class_alias('CoreWine\TemplateEngine\Engine', 'Engine');


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


		
	$view = Router::load();

	if(empty($view)){
		die("Current Router doens't have a view");
	}


	$s = Engine::startRoot();
	include $view;
	Engine::endRoot();


   	//\CoreWine\Debug::print();
   	


?>