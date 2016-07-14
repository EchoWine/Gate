<?php

namespace CoreWine\Api\Response;

use CoreWine\Http\Request;

class ApiAddError extends Error{

	/** 
	 * Code
	 */
	const CODE = 'error';

	/**
	 * Message
	 */
	const MESSAGE = "An error was occurred";

	/**
	 * Construct
	 */
	public function __construct(){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setRequest(Request::getCall());

	}
}

?>