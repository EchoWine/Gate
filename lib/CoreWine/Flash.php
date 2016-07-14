<?php

namespace CoreWine;

use CoreWine\Http\Request;

/**
 * Flash messages.
 */
class Flash{

	/**
	 * Add
	 *
	 * @param String $type
	 * @param String $message
	 */
	public static function add($type,$message){
		
		$messages = Flash::getAll();
		$messages[$type][] = $message;
		Request::setSession('flash',json_encode($messages));
	}

	/**
	 * Get all message of an specific type.
	 *
	 * @param String $type
	 */
	public static function get($type){

		$messages = Flash::getAll();
		Flash::remove($messages,$type);
		return isset($messages[$type]) ? $messages[$type] : [];
	}


	/**
	 * Retrieve a listing of the messages.
	 *
	 */
	public static function getAll(){

		return json_decode(Request::getSession('flash'),true);
	}

	/**
	 * Remove all messages from a given type.
	 *
	 * @param String $type
	 * @param String $message
	 */
	private static function remove($messages,$type){

		unset($messages[$type]);
		Request::setSession('flash',json_encode($messages));
	}
}

?>