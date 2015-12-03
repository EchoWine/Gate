<?php

class AuthView{
	
	public $model;
	public $controller;
	
	public function __construct($model,$controller){
		$this -> model = $model;
		$this -> controller = $controller;
	}

	public function printFormLogin(){
		$controller -> cfg['user'];
	}

	
	public static function forceLogin($path){

		$path = $path."/templates";

		# If not logged
		if(true){
			TemplateEngine::overwrite('main',$path,'auth.login');

			TemplateEngine::aggregate('style',$path,'auth.style');
		}
	}
}

?>