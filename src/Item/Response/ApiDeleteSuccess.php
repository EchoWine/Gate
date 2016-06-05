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
	const MESSAGE = "Resource was deleted with success";

	/**
	 * Construct
	 *
	 * @param int $id
	 * @param array $old
	 * @param array $new
	 */
	public function __construct($id,$data){

		parent::__construct(self::CODE,self::MESSAGE);
		$this -> setData(['id' => $id,'resource' => $data]) -> setRequest(Request::getCall());

	}
}

?>