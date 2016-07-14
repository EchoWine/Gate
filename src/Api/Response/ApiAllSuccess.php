<?php

namespace Api\Response;

use CoreWine\Http\Request;

class ApiAllSuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'success';

	/**
	 * Message
	 */
	const MESSAGE = "Resources retrieved with success";

	/**
	 * Construct
	 *
	 * @param array $data
	 */
	public function __construct($data){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData($data) -> setRequest(Request::getCall());

	}
}

?>