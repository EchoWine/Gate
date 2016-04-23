<?php

namespace Example\Controller;

use CoreWine\Route as Route;

use CoreWine\SourceManager\Controller as Controller;

class ExampleController extends Controller{

	public function __routes(){
		Route::get('/',['as' => 'index','callback' => 'index']);
	}

	public static function index(){
		return view('Example/index',['yoho' => 'YoHo!!!!!']);
	}
	
}

?>