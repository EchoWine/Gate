<?php

namespace Kernel\Exceptions;

use CoreWine\View\Engine;
use CoreWine\Http\Request;
use CoreWine\Http\Controller as Controller;

class ExceptionHandler extends Controller{


	public function report($exception){
		

	}
	

	public function render($exception){

		$class = basename(get_class($exception));
		
		return $this -> view('exception',['e' => $exception,'class' => $class]);
	}
}

?>