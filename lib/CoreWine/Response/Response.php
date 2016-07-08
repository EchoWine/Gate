<?php

namespace CoreWine\Response;

class Response{
		
	/**
	 * Body of response
	 *
	 * @var mixed
	 */
	public $body;

	/**
	 * Headers
	 *
	 * @var array
	 */
	public $headers = [];

	/**
	 * Construct
	 */
	public function __construct(){}

	/** 
	 * Add header
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function header($name,$value){
		$this -> headers[$name] = $value;
	}

	/**
	 * Set body
	 *
	 * @param mixed $body
	 */
	public function setBody($body){
		$this -> body = $body;
	}

	/**
	 * Get body
	 *
	 * @return mixed
	 */
	public function getBody(){
		return $this -> body;
	}

	/**
	 * Get headers
	 *
	 * @return mixed
	 */
	public function getHeaders(){
		return $this -> headers;
	}

	/** 
	 * Send response
	 */
	public function send(){
		$this -> sendHeaders();
		$this -> sendBody();
	}

	/**
	 * Send Headers
	 */
	public function sendHeaders(){
		foreach($this -> getHeaders() as $name => $value){
			header($name.": ".$value);
		}
	}

	/**
	 * Send body
	 */
	public function sendBody(){
		echo $this -> getBody();
	}
}