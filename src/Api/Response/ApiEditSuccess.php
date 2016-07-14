<?php

namespace Api\Response;

use CoreWine\Http\Request;

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
	 * @param ORM\Model $model
	 * @param ORM\Model $old
	 */
	public function __construct($model,$old){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData(['id' => $model -> id,'old' => $old -> toArray(),'resource' => $model -> toArray()]) -> setRequest(Request::getCall());

	}
}

?>