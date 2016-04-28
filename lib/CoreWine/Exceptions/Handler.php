<?php

namespace CoreWine\Exceptions;
use Exception;
use CoreWine\TemplateEngine\Engine;

use CoreWine\Exceptions\FatalException;
use CoreWine\Exceptions\ErrorException;

class Handler{

	public $renderClass;
	public $renderMethod;

	public function __construct($renderClass,$renderMethod){
		$this -> renderClass = $renderClass;
		$this -> renderMethod = $renderMethod;
		$this -> register();
	}

	public function register(){
		error_reporting(-1); 

		set_error_handler([$this,'reportError']);
		//set_exception_handler([$this,'reportException']);
		//register_shutdown_function([$this,'reportFatal']);

	}

	public function report(Exception $e){

	}
	
	public function reportFatal(){
		$error = error_get_last();
    	
    	if($error["type"] == E_ERROR){
    		$this -> reportException(new FatalException($error['message'],$error['type'],$error['file'],$error['line']));
    	}
	}
	
	public function reportError($errno, $str, $file, $line, $context = null){

    	$this -> reportException(new ErrorException($str,$errno,$file,$line));
	}

	public function reportException(Exception $e){
		$this -> render($e);
	}

	public function render(Exception $e){
		$class = basename(get_class($e));
		
		echo "<html><body style='background:#efefef'><div style='margin:30px auto;max-width:800px'>";
		echo "
		<div style='padding:20px;background:white;border-radius:15px;font-size:22px;border:1px solid #dedede'>

			<span style='font-size:21px; display:block'>{$class} {$e -> getFile()} in line {$e -> getLine()}</span>
			<span style='font-size:21px;'>{$e -> getMessage()}  </span>

		</div>
		";
		echo "<div style='margin-top:20px;padding:20px;background:white;border-radius:15px;font-size:22px;border:1px solid #dedede'>";
			echo "<div> StackTrace </div>";

			echo "<div style='font-size:14px'>";
				echo "1. ";
				echo "{$e -> getFile()} in line {$e -> getLine()}";
			echo "</div>";

			$i = 2;
			foreach($e -> getTrace() as $k){
				echo "<div style='font-size:14px'>";
					echo "{$i}. ";
					echo "{$k['file']} in line {$k['line']}";
					$i++;
				echo "</div>";
			}
		echo "</div>";
		echo "</body></html>";
			die();
	}
}



?>