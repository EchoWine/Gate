<?php

namespace Api\Response;

use CoreWine\Http\Request;

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

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setRequest(Request::getCall());
	}
}

?>