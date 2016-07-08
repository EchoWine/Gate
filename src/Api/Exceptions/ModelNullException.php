<?php

namespace Api\Exceptions;

use CoreWine\Exceptions\Exception;

class ModelNullException extends Exception{

	protected $message = 'You must define a model in Controller';
	
}
?>