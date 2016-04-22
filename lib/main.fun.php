<?php
	
	use CoreWine\Route as Route;
	use CoreWine\Request as Request;
	
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

	function view($file,$data = []){
		Route::view($data);
		return TemplateEngine::html($file);
	}

	function assets($url,$module = ''){

		$base = !empty($module) ? Request::getDirUrl().'../src/'.$module.'/Resources/public/' : Request::getDirUrl();
		return $base.$url;
	}

	function post($name){
		$post = Request::post($name);
		return $post != null ? $post : '';
	}

?>