<?php

class AuthView extends View{
	
	public $model;
	public $controller;
	
	public function __construct($model,$controller){
		$this -> model = $model;
		$this -> controller = $controller;
	}

	public function printFormLogin(){
		$controller -> cfg['user'];
	}

	
	public function forceLogin($path){

		$path = $path."/templates";

		TemplateEngine::overwrite('main','auth.login','!$logged');

		// TemplateEngine::aggregate('style',$path,'auth.style');
		
	}
}

?>