<?php

namespace Item\Response;

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

		parent::__construct(self::CODE,sprintf(self::MESSAGE,$label,$value));
		$this -> setData(['label' => $label,'value' => $value]);
		$this -> setRequest(Request::getCall());
	}
}

?>