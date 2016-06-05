<?php

namespace Item\Response;

use CoreWine\Request;

class ApiGetSuccess extends Success{

	/** 
	 * Code
	 */
	const CODE = 'success';

	/**
	 * Message
	 */
	const MESSAGE = "Resource was retrieved with success";

	/**
	 * Construct
	 *
	 * @param int $id
	 * @param array $old
	 * @param array $new
	 */
	public function __construct($id,$resource){

		parent::__construct(self::CODE,self::MESSAGE);
		$this -> setData(['id' => $id,'resource' => $resource]) -> setRequest(Request::getCall());

	}
}

?>