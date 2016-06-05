<?php

namespace Item\Response;

use CoreWine\Request;

class ApiAllErrorParamShow extends Error{

	/** 
	 * Code
	 */
	const CODE = 'show_invalid';

	/**
	 * Message
	 */
	const MESSAGE = "the parameter show is invalid";

	/**
	 * Construct
	 */
	public function __construct(){

		parent::__construct(self::CODE,self::MESSAGE);
		$this -> setRequest(Request::getCall());
	}
}

?>