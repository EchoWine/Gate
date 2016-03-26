<?php

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
?>