<?php

namespace Api\Exceptions;

use Exception;

class ModelNullException extends Exception{

	public function __construct($class){

		$this -> message = "You must define a model in $class";

		parent::__construct();
	}
	
}
?>