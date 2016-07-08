<?php

namespace CoreWine\SourceManager;

use CoreWine\Router;
use CoreWine\TemplateEngine\Engine;
use CoreWine\Exceptions as Exceptions;
use CoreWine\TemplateEngine\Response as ViewResponse;

class Controller{
	
	/**
	 * Router
	 */
	public function __routes(){}

	public function __check(){}
	
	public function view($file,$data = []){
		Router::view($data);

		$response = new ViewResponse();
		$response -> setContent(Engine::html($file));
		return $response;
	}

	public function route($controller = null){

		if($controller !== null){
			return Router::any() -> callback(function() use($controller){
				if(!method_exists($this,$controller)){
					throw new Exceptions\RouteException("No method $controller; Check __routes() definition");
				}
				return call_user_func_array(array($this,$controller), func_get_args());
			});
		}
	}


}
?>