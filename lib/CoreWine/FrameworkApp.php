<?php

namespace CoreWine;

use CoreWine\Components\App;

use Request;
use Router;
use Flash;
use Engine;
use DB;
use Manager;

class FrameworkApp extends App{

	public function __construct(){
		
		class_alias('CoreWine\Request', 'Request');
		class_alias('CoreWine\Router', 'Router');
		class_alias('CoreWine\Flash', 'Flash');
		class_alias('CoreWine\TemplateEngine\Engine', 'Engine');
		class_alias('CoreWine\SourceManager\Manager', 'Manager');
		class_alias('CoreWine\DataBase\DB', 'DB');
	}

	public function app(){
		
		

		# Load template
		Engine::ini(PATH."/".PATH_STORAGE);


		# Load template

		DB::connect(include PATH_CONFIG.'/database.php');

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