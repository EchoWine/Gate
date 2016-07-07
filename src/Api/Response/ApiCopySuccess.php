<?php

namespace Api\Response;

use CoreWine\Request;

class ApiCopySuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'sucess';

	/**
	 * Message
	 */
	const MESSAGE = "Resource was copied with success";

	/**
	 * Construct
	 *
	 * @param ORM\Model $new_model
	 * @param ORM\Model $from_model
	 */
	public function __construct($new_model,$from_model){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData(['id' => $new_model -> id,'resource' => $new_model -> toArray(),'from' => $from_model -> toArray()]) -> setRequest(Request::getCall());

	}
}

?>