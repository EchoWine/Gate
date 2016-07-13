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

	/**
	 * Check
	 */
	public function __check(){}
	
	/**
	 * Return a ViewResponse
	 *
	 * @param string $file
	 * @param array $data
	 *
	 * @return ViewResponse
	 */
	public function view($file,$data = []){
		Router::view($data);

		$response = new ViewResponse();
		$response -> setBody(Engine::html($file));
		return $response;
	}

	/**
	 * Set a route
	 *
	 * @param string $method
	 *
	 * @return Route
	 */
	public function route($method = null){

		if($method !== null){
			return Router::any() -> callback(function() use($methodr){
				if(!method_exists($this,$method)){
					throw new Exceptions\RouteException("No method $method; Check __routes() definition");
				}
				return call_user_func_array(array($this,$method), func_get_args());
			});
		}
	}


}
?>