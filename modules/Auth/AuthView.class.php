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

		if(true){
			Module::TemplateOverwrite('main','login');
		}
		
	}

	public function setHeader($path){
		Module::TemplateAggregate('header-nav',$path,'header-nav',30);
	}
}

?>