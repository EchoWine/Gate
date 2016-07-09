<?php

namespace Api\Exceptions;

use CoreWine\Exceptions\Exception;

class UrlNullException extends Exception{

	public function __construct($class){

		$this -> message = "You must define an url in $class";

		parent::__construct();
	}
	
}
?>