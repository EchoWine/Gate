<?php

namespace Api\Exceptions;

use CoreWine\Exceptions\Exception;

class UrlNullException extends Exception{

	protected $message = 'You must define an url in Controller';
	
}
?>