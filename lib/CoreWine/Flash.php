<?php

namespace CoreWine;

class Flash{

	public static function add($type,$message){
		
		$messages = Flash::getAll();
		$messages[$type][] = $message;
		Request::setSession('flash',json_encode($messages));
	}

	public static function get($type){

		$messages = Flash::getAll();
		Flash::remove($messages,$type);
		return isset($messages[$type]) ? $messages[$type] : [];
	}

	public static function getAll(){

		return json_decode(Request::getSession('flash'),true);
	}

	private static function remove($messages,$type){

		unset($messages[$type]);
		Request::setSession('flash',json_encode($messages));
	}
}

?>