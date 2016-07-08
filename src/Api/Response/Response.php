<?php

namespace Api\Response;

use CoreWine\Response\JSONResponse as JSONResponse;

class Response extends JSONResponse{

	public $status;
	public $code;
	public $message;
	public $details;
	public $data;
	public $request;


	public function __construct($code = null,$message = null){
		$this -> code = $code;
		$this -> message = $message;
		parent::__construct();

	}

	public function setDetails($details){
		$this -> details = $details;
		return $this;
	}

	public function setData($data){
		$this -> data = $data;
		return $this;
	}

	public function setRequest($request){
		$this -> request = $request;
		return $this;
	}

	public function getData(){
		return $data;
	}

	public function getContent(){
		return (object)[
			'status' => $this -> status,
			'code' => $this -> code,
			'message' => $this -> message,
			'details' => $this -> details,
			'data' => $this -> data,
			'request' => $this -> request
		];
	}
}

?>