<?php

namespace CoreWine\Exceptions;
use Exception;
use CoreWine\TemplateEngine\Engine;

use CoreWine\Exceptions\FatalException;
use CoreWine\Exceptions\ErrorException;

class Handler{

	public $renderClass;
	public $renderMethod;

	public function __construct(){
		$this -> register();
	}

	public function register(){

		set_exception_handler([$this,'report']);
		//ini_set( "display_errors", "off" );
		error_reporting( E_ALL );
		set_error_handler([$this,'error']);

	}

	public function error($errno, $errstr, $errfile, $errline){
		throw new FatalException($errstr, '', $errno, $errfile, $errline);
	}


	public function report($e){
		$this -> render($e);
	}
	
	public function render($e){
		$class = basename(get_class($e));
		
		include dirname(__FILE__)."/files/error.php";
		die();
	}
}

?>