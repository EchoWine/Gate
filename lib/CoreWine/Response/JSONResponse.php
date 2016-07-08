<?php

namespace CoreWine\Response;

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
	public function sendContent(){
		echo json_encode($this -> getContent(),JSON_PRETTY_PRINT);
	}

}