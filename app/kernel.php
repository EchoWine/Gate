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

	include "../vendor/autoload.php";

	
	class_alias("CoreWine\Component\Bag","Bag");
	class_alias("CoreWine\Component\Collection","Collection");
	class_alias("CoreWine\Component\Flash","Flash");
	class_alias("CoreWine\Http\Request","Request");
	class_alias("CoreWine\Http\Response\Response","Response");
	class_alias("CoreWine\Loader\Component\Service","Service");


	new \CoreWine\Exceptions\Handler('ExceptionsController','render');


	\CoreWine\DataBase\DB::connect(include PATH_CONFIG.'/database.php');

	\CoreWine\View\Engine::ini(PATH_APP."/cache/views");

	\CoreWine\DataBase\ORM\SchemaBuilder::setFields(include PATH_CONFIG.'/orm.php');
	
	# Load all sources
	\CoreWine\Loader\Manager::loadAll(PATH_SRC);
	
	

	if(php_sapi_name() == "cli")
		return;


	\CoreWine\Loader\Manager::callControllersRoutes();
	\CoreWine\Http\Router::setRequest();
	\CoreWine\Loader\Manager::callControllersChecks();


	# Compile
	\CoreWine\View\Engine::compile(PATH_APP,'Resources/views');


	foreach(\CoreWine\Loader\Manager::$list as $name => $dir){
		\CoreWine\View\Engine::compile(
			PATH_APP,
			"Resources/".$name."/views",
			$name
		);
	}

	foreach(\CoreWine\Loader\Manager::$list as $name => $dir){
		\CoreWine\View\Engine::compile(
			PATH_SRC,
			$name."/Resources/views",
			$name
		);
	}

	\CoreWine\View\Engine::translates();

	$response = \CoreWine\Http\Router::load();

	
	if(empty($response))
		die("Current Router must return a Response");
	

	$response -> send();
	

?>