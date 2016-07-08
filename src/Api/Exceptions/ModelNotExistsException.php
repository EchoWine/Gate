<?php

namespace Api\Exceptions;

use CoreWine\Exceptions\Exception;

class ModelNotExistsException extends Exception{
	
	public function __construct($model){

		$this -> message = "You have defined a model: {$model} that doesn't exist in your Controller";

		parent::__construct($model);
	}
}
?>