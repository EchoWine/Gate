<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiErrorParamSortNotValid extends Error{

	/** 
	 * Code
	 */
	const CODE = 'sort_field_invalid';

	/**
	 * Message
	 */
	const MESSAGE = "The field sent as sort doensn't support sort";

	/**
	 * Construct
	 */
	public function __construct(){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setRequest(Request::getCall());
	}
}

?>