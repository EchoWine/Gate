<?php

namespace Api\Response;

use CoreWine\Request;

class ApiDeleteSuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'success';

	/**
	 * Message
	 */
	const MESSAGE = "Resource was deleted with success";

	/**
	 * Construct
	 *
	 * @param int $id
	 * @param array $resource
	 */
	public function __construct($id,$resource){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData(['id' => $id,'resource' => $resource]) -> setRequest(Request::getCall());

	}
}

?>