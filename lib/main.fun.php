<?php

	function isJson($s){
		json_decode($s);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	function is_closure($t) {
    	return is_object($t) && ($t instanceof Closure);
	}

?>