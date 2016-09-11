<?php

namespace CoreWine\Exceptions;

class UndefinedMethodException extends Exception{


	public function __construct($class,$method){

		$message = "Call to undefined method {$class}::{$method}()";

		parent::__construct($message);
	}


}
?>