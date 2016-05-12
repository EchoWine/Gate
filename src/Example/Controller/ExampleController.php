<?php

namespace Example\Controller;

use CoreWine\Router;

use CoreWine\SourceManager\Controller as Controller;

class ExampleController extends Controller{

	public function __routes(){
		$this -> route('/',['as' => 'index','__controller' => 'index']);
	}

	public function index(){
		return $this -> view('Example/index',['yoho' => 'YoHo!!!!!']);
	}
	
}

?>