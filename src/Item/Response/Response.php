<?php

namespace Item\Response;

class Response{

	public $status;
	public $code;
	public $message;
	public $details;
	public $data;
	public $request;


	public function __construct($code = null,$message = null){
		$this -> code = $code;
		$this -> message = $message;
		return $this;

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
}

?>