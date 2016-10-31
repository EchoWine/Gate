<?php
	
	use Kernel\Exceptions\Handler;

	# Error reporting
	error_reporting(-1);
	ini_set('display_errors', 'On');
	ini_set("log_errors", 1);
	ini_set("error_log", __DIR__."/php-error.log");
	ini_set("date.timezone", "Europe/Rome");

	# Path
	define('DIR',__DIR__."/");
	define('PATH',DIR.'../public');
	define('PATH_PUBLIC',DIR.'../public');
	define('PATH_APP',DIR.'../app');
	define('PATH_SRC',DIR.'../src');
	define('PATH_LIB',DIR.'../lib');
	define('PATH_CONFIG',DIR.'../config');

	include DIR."../vendor/autoload.php";

	
	class_alias("Kernel\Service","Service");

	
	class_alias("CoreWine\Component\Bag","Bag");
	class_alias("CoreWine\Component\Collection","Collection");
	class_alias("CoreWine\Http\Flash","Flash");
	class_alias("CoreWine\Http\Request","Request");
	class_alias("CoreWine\Http\Response\Response","Response");
	class_alias("CoreWine\Component\Cache","Cache");
	class_alias("CoreWine\Component\Cfg","Cfg");

	# Make dir cache if doesn't exists


	# Add cache dir 
	Cache::setDir(PATH_APP.'/cache/data');

	# Initialize Handler
	Handler::register();
	Handler::add(\Kernel\Exceptions\ExceptionHandler::class);
	
	# Initialize request
	Request::ini();

	# Initialize config

	$config = include PATH_CONFIG.'/app.php';
	
	Cfg::add("app",$config);

	\CoreWine\View\Engine::ini(PATH_APP."/cache/views");

	\CoreWine\DataBase\DB::connect($config['database']);


	# Compile
	\CoreWine\View\Engine::compile(PATH_APP,'Resources/views');
	
	foreach(\Kernel\Manager::$list as $name => $dir){
		\CoreWine\View\Engine::compile(
			PATH_APP,
			"Resources/".$name."/views",
			$name
		);
	}

	\CoreWine\DataBase\ORM\SchemaBuilder::setFields(include PATH_CONFIG.'/orm.php');
	
	# Load all sources
	\Kernel\Manager::loadAll(PATH_SRC);

	foreach(\Kernel\Manager::$list as $name => $dir){
		\CoreWine\View\Engine::compile(
			PATH_SRC,
			$name."/Resources/views",
			$name
		);
	}



	Cfg::set('app.path.drive.public',__DIR__."/../".Cfg::get('app.public'));
	Cfg::set('app.web',Cfg::get('app.root').Cfg::get('app.public'));

	# File Path
	\CoreWine\DataBase\ORM\Field\File\Schema::setDefaultFilePath(__DIR__."/../".Cfg::get('app.public')."uploads/");

	# Web Path
	\CoreWine\DataBase\ORM\Field\File\Schema::setDefaultWebPath(Cfg::get('app.root').Cfg::get('app.public')."uploads/");

	\CoreWine\View\Engine::translates();
	
	if(php_sapi_name() == "cli")
		return;

	\Kernel\Manager::callControllersRoutes();
	\CoreWine\Http\Router::setRequest();
	\Kernel\Manager::callControllersChecks();


	$response = \CoreWine\Http\Router::load();

	
	if(empty($response))
		die("Current Router must return a Response");
	
	$response -> send();
	

?>