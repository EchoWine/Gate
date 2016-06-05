<?php

namespace Item\Response;

use CoreWine\Request;

class ApiAddSuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'success';

	/**
	 * Message
	 */
	const MESSAGE = "Resource was added with success";

	/**
	 * Construct
	 *
	 * @param int $id
	 * @param array $old
	 * @param array $new
	 */
	public function __construct($id,$new){

		parent::__construct(self::CODE,self::MESSAGE);
		$this -> setData(['id' => $id,'resource' => $new]) -> setRequest(Request::getCall());

	}
}

?>