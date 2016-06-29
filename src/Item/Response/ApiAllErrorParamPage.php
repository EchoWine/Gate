<?php

namespace Item\Response;

use CoreWine\Request;

class ApiAllErrorParamPage extends Error{

	/** 
	 * Code
	 */
	const CODE = 'page_invalid';

	/**
	 * Message
	 */
	const MESSAGE = "the parameter page is invalid";

	/**
	 * Construct
	 */
	public function __construct(){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setRequest(Request::getCall());
	}
}

?>