<?php

namespace CoreWine\Response;

class Response{
		
	/**
	 * Content of response
	 *
	 * @var mixed
	 */
	public $content;

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
	 * Set content
	 *
	 * @param mixed $content
	 */
	public function setContent($content){
		$this -> content = $content;
	}

	/**
	 * Get content
	 *
	 * @return mixed
	 */
	public function getContent(){
		return $this -> content;
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
		$this -> sendContent();
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
	 * Send content
	 */
	public function sendContent(){
		echo $this -> getContent();
	}
}