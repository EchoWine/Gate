<?php

namespace Item\Response;

use CoreWine\Request;

class ApiFieldErrorNotUnique extends Error{

	/** 
	 * Code
	 */
	const CODE = 'fields_not_unique';

	/**
	 * Message
	 */
	const MESSAGE = "the value of field %s already exists";

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