<?php
	
	use CoreWine\Http\Router;
	use CoreWine\Http\Request;
	use CoreWine\View\Engine;
	use CoreWine\Http\Exceptions\RouteException;
	use CoreWine\Http\Response\RedirectResponse;


	
	function isJson($s){
		json_decode($s);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	function is_closure($t) {
		return is_object($t) && ($t instanceof Closure);
	}
	
	function dirname_r($f,$i) {
    	for(;$i>0;$i--)
    		$f = dirname($f);

    	return $f;
	}

	function route($route = null,$params = []){
		return $route == null ? Router::active() : Router::url($route,$params);
	}

	function assets($url){
		return Request::getDirUrl().$url;
	}

	function post($name){
		$post = Request::post($name);
		return $post != null ? $post : '';
	}

	function redirect($url){
		return new RedirectResponse($url);
	}

	function brackets($name){
		return '{'.$name.'}';
	}
	
	function abort($code){
		throw new RouteException();
	}

	function web($url = ''){
		return Cfg::get('app.web').$url;
	}

	function drive($url = ''){
		return Cfg::get('app.drive').$url;
	}

	function media($url = ''){
		return Cfg::get('app.drive')."public/uploads/".$url;
	}

?>