<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiFieldsInvalid extends Error{

	/** 
	 * Code
	 */
	const CODE = 'fields_invalid';

	/**
	 * Message
	 */
	const MESSAGE = "The values sent aren't valid";

	/**
	 * Construct
	 *
	 * @param array $details
	 */
	public function __construct($details){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setDetails($details);
		$this -> setRequest(Request::getCall());
	}
}

?>