<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiError extends Error{

	/** 
	 * Code
	 */
	const CODE = 'error';

	/**
	 * Construct
	 *
	 * @param string $message
	 */
	public function __construct(string $message){

		parent::__construct(static::CODE,$message);
		$this -> setRequest(Request::getCall());
	}
}

?>