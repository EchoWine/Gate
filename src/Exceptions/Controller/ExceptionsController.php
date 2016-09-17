<?php

namespace Exceptions\Controller;

use Exception;

use Exceptions\FatalException;
use CoreWine\Http\Router;
use CoreWine\Http\Controller;

class ExceptionsController extends Controller{

	public function __check(){
		new \CoreWine\Exceptions\Handler($this,'render');
	}

	public function render(Exception $e){
		return $this -> view('Exceptions/Error');
	}
}



?>