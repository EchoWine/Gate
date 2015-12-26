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

		if(false){
			Module::TemplateOverwrite('main','login');
		}
		
	}

	public function setHeader(){
		Module::TemplateAggregate('header-nav','header-nav',30);
	}
}

?>