<?php

namespace CoreWine\Http\Response;

class JSONResponse extends Response{

	/**
	 * Construct
	 */
	public function __construct(){
		parent::__construct();
		$this -> header('Content-Type','application/json');
	}

	/**
	 * Set content
	 */
	public function sendBody(){
		echo json_encode($this -> getBody(),JSON_PRETTY_PRINT);
	}

}