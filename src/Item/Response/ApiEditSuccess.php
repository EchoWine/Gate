<?php

namespace Item\Response;

use CoreWine\Request;

class ApiEditSuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'success';

	/**
	 * Message
	 */
	const MESSAGE = "Resource was edited with success";

	/**
	 * Construct
	 *
	 * @param int $id
	 * @param array $old
	 * @param array $resource
	 */
	public function __construct($id,$old,$resource){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData(['id' => $id,'old' => $old,'resource' => $resource]) -> setRequest(Request::getCall());

	}
}

?>