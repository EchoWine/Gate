<?php

namespace CoreWine\SourceManager;

use CoreWine\Route;
use CoreWine\TemplateEngine\Engine;

class Controller{
	
	/**
	 * Route
	 */
	public function __routes(){}

	public function __check(){}
	
	public function view($file,$data = []){
		Route::view($data);
		return Engine::html($file);
	}

	public function json($var){
		echo json_encode($var);
		die();
	}
	public function route($route,$params){

		if(!isset($params['callback']) && isset($params['__controller'])){
			$controller = $params['__controller'];
			$params['callback'] = function() use($controller){
				return call_user_func_array(array($this,$controller), func_get_args());
			};
		}
		
		Route::get($route,$params);
	}


}
?>