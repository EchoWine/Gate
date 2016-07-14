<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiException extends Error{

	/** 
	 * Code
	 */
	const CODE = 'exception';

	/**
	 * Construct
	 *
	 * @param exception $e
	 */
	public function __construct(\Exception $e){

		parent::__construct(static::CODE,$e -> getMessage());
		$this -> setRequest(Request::getCall());
	}
}

?>