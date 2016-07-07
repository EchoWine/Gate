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
	 * @param ORM\Model $model
	 */
	public function __construct($model){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData(['id' => $model -> id,'resource' => $model -> toArray()]) -> setRequest(Request::getCall());

	}
}

?>