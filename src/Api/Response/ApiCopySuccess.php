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
	 * @param int $id
	 * @param array $from
	 * @param array $new
	 */
	public function __construct($id,$from,$new){

		parent::__construct(static::CODE,static::MESSAGE);
		$this -> setData(['id' => $id,'from' => $from,'resource' => $new]) -> setRequest(Request::getCall());

	}
}

?>