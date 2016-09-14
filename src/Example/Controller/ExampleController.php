<?php

namespace Example\Controller;

use CoreWine\Http\Router;

use CoreWine\Http\Controller as Controller;

class ExampleController extends Controller{

	public function __routes(){
		$this -> route('index') -> url('/');
	}

	public function index(){
		return $this -> view('Example/index',['yoho' => 'YoHo!!!!!']);
	}
	
}

?>