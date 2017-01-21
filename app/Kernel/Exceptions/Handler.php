<?php

namespace Kernel\Exceptions;

use CoreWine\View\Engine;
use CoreWine\Http\Request;
use CoreWine\Http\Response\Response;

class Handler{

	public static $handlers = [];

	public static function register(){
		set_exception_handler([self::class,'handle']);
		//ini_set( "display_errors", "off" );
		error_reporting( E_ALL );
		set_error_handler([self::class,'error']);
		register_shutdown_function([self::class,'fatal']);
	}

	public static function fatal(){
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
			self::error($errno,$errstr,$errfile,$errline);
		}
	}

	public static function error($errno, $errstr, $errfile, $errline){
		throw new FatalErrorException($errstr, '', $errno, $errfile, $errline);
	}
	
	public static function add($handler){
		static::$handlers[] = new $handler();
	}

	public static function handle($e){
		$handlers = array_reverse(self::$handlers);
		
		$e_container = Exception::cloneFrom($e);
		$e_container -> setFile(Engine::getFileNameByCache(basename($e -> getFile(),".php")));


		foreach($handlers as $handler){
			$handler -> report($e);
		}

		# if CLI print the message without render
		if(php_sapi_name() == "cli"){
			
			echo $e;
			return;
		}

		foreach($handlers as $handler){
			$response = $handler -> render($e);

			if($response){
				if($response instanceof Response){
					
					return $response -> send();
				
				}else{

				}
			}
		}

	}
	
}

?>