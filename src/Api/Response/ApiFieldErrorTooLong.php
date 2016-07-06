<?php

namespace Api\Response;

use CoreWine\Request;

class ApiFieldErrorTooLong extends Error{

	/** 
	 * Code
	 */
	const CODE = 'field_invalid_too_long';

	/**
	 * Message
	 */
	const MESSAGE = "%s is too long (max: %s)";

	/**
	 * Construct
	 *
	 * @param array $details
	 */
	public function __construct($label,$value){

		parent::__construct(static::CODE,sprintf(static::MESSAGE,$label,$value));
		$this -> setData(['label' => $label,'value' => $value]);
		$this -> setRequest(Request::getCall());
	}
}

?>