<?php

namespace CoreWine\Loader;

use CoreWine\Component\App;

use CoreWine\TemplateEngine\Engine;
use CoreWine\Http\Request;
use CoreWine\Component\Flash;
use CoreWine\Http\Router;
use CoreWine\Loader\Manager;
use CoreWine\Http\Response;

class Application extends App{

	public function __construct(){
		class_alias("CoreWine\Component\Bag","Bag");
		class_alias("CoreWine\Component\Collection","Collection");
		class_alias("CoreWine\Component\Flash","Flash");
		class_alias("CoreWine\Http\Request","Request");
		class_alias("CoreWine\Http\Response\Response","Response");
		class_alias("CoreWine\Loader\Component\Service","Service");
	}

	public function app(){
		
		# Load all sources
		Manager::loadAll(PATH_SRC);
		
		

		if(php_sapi_name() == "cli")
			return;


		Manager::callControllersRoutes();
		Router::setRequest();
		Manager::callControllersChecks();


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

		$response = Router::load();

		
		if(empty($response))
			die("Current Router must return a Response");
		

		$response -> send();
	

	}

}

?>