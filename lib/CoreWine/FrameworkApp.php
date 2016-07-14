<?php

namespace CoreWine;

use CoreWine\Components\App;

use CoreWine\TemplateEngine\Engine;
use CoreWine\Http\Request;
use CoreWine\Flash;
use CoreWine\Http\Router;
use CoreWine\SourceManager\Manager;
use CoreWine\Http\Response;

class FrameworkApp extends App{

	public function __construct(){
		class_alias("CoreWine\Flash","Flash");
	}

	public function app(){
		
		# Load all sources
		Manager::loadAll(PATH_SRC);
			
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