<?php

namespace Api\Exceptions;

use CoreWine\Exceptions\Exception;

class ModelNotExistsException extends Exception{
	
	public function __construct($class,$model){

		$this -> message = "You have defined a model: {$model} that doesn't exist in $class";

		parent::__construct();
	}
}
?>