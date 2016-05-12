<?php

namespace CoreWine\SourceManager;

use CoreWine\Router;
use CoreWine\TemplateEngine\Engine;

class Controller{
	
	/**
	 * Router
	 */
	public function __routes(){}

	public function __check(){}
	
	public function view($file,$data = []){
		Router::view($data);
		return Engine::html($file);
	}

	public function json($var){
		header('Content-Type: application/json');
		echo json_encode($var,JSON_PRETTY_PRINT);
		die();
	}
	public function route($controller = null){

		if($controller !== null){
			return Router::any() -> callback(function() use($controller){
				return call_user_func_array(array($this,$controller), func_get_args());
			});
		}
	}


}
?>