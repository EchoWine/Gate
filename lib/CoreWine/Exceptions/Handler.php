<?php

namespace CoreWine\Exceptions;

use CoreWine\TemplateEngine\Engine;
use CoreWine\Http\Request;

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
		register_shutdown_function([$this,'fatal_handler']);

	}

	public function fatal_handler(){
		$errfile = "unknown file";
		$errstr  = "shutdown";
		$errno   = E_CORE_ERROR;
		$errline = 0;

		$error = error_get_last();

		if( $error !== NULL) {
			$errno   = $error["type"];
			$errfile = $error["file"];
			$errline = $error["line"];
			$errstr  = $error["message"];
			$this -> error($errno,$errstr,$errfile,$errline);
		}
	}

	public function error($errno, $errstr, $errfile, $errline){
		throw new FatalErrorException($errstr, '', $errno, $errfile, $errline);
	}


	public function report($e){
		error_log("Caught $e");
		$this -> render($e);
	}
	
	public function render($e){
		if(Request::getMethod()){
			$class = basename(get_class($e));
			
			include dirname(__FILE__)."/files/error.php";
			die();
		}else{
			print_r($e);
		}
	}
}

?>