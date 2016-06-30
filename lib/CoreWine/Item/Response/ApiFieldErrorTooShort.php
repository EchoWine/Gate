<?php

namespace Item\Response;

use CoreWine\Request;

class ApiFieldErrorTooShort extends Error{

	/** 
	 * Code
	 */
	const CODE = 'field_invalid_too_short';

	/**
	 * Message
	 */
	const MESSAGE = "%s is too short (min: %s)";

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