<?php

namespace CoreWine;

use CoreWine\Components\App;

use CoreWine\TemplateEngine\Engine;
use CoreWine\Request;
use CoreWine\Flash;
use CoreWine\Router;
use CoreWine\SourceManager\Manager;

class FrameworkApp extends App{

	public function __construct(){
		
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

		$view = Router::load();

		if(empty($view)){
			die("Current Router doens't have a view");
		}

		foreach($GLOBALS as $n => $k){
			$$n = $k;
		}

		$s = Engine::startRoot();
		include $view;
		Engine::endRoot();


	}

}

?>