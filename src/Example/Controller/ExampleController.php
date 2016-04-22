<?php

namespace Example\Controller;

use CoreWine\Route as Route;

class ExampleController{

	public function __routes(){
		Route::get('/',['as' => 'index','callback' => 'index']);
	}

	public static function index(){
		return view('Example/index',['yoho' => 'YoHo!!!!!']);
	}
	
}

?>