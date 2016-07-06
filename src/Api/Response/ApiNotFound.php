<?php

namespace Api\Response;

use CoreWine\Request;

class ApiNotFound extends Error{

	/** 
	 * Code
	 */
	const CODE = 'not_found';

	/**
	 * Message
	 */
	const MESSAGE = "Resource not found";

	/**
	 * Construct
	 */
	public function __construct(){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setRequest(Request::getCall());
	}
}

?>