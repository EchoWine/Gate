<?php

namespace Api\Exceptions;

use Exception;

class UrlNullException extends Exception{

	public function __construct($class){

		$this -> message = "You must define an url in $class";

		parent::__construct();
	}
	
}
?>