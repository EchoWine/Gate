<?php

namespace Exceptions;

use Exception;

use Exceptions\FatalException;
use CoreWine\Route;
use CoreWine\SourceManager\Controller;

class ExceptionsController extends Controller{

	public function __check(){
		new CoreWine\Exceptions\Handler($this,'render');
	}

	public function render(Exception $e){
		return $this -> view('Exceptions/Error');
	}
}



?>