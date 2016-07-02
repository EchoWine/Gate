<?php

namespace Item\Response;

use CoreWine\Request;

class ApiFieldErrorInvalid extends Error{

	/** 
	 * Code
	 */
	const CODE = 'field_invalid_value';

	/**
	 * Message
	 */
	const MESSAGE = "%s is invalid";

	/**
	 * Construct
	 *
	 * @param array $details
	 */
	public function __construct($label,$value){

		parent::__construct(static::CODE,sprintf(static::MESSAGE,$label));
		$this -> setData(['label' => $label,'value' => $value]);
		$this -> setRequest(Request::getCall());
	}
}

?>