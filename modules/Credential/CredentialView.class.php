<?php

class CredentialView extends View{
	
	public $model;
	public $controller;
	public $path;
	
	public function template(){

		TemplateEngine::overwrite('content','Credential.page','$pageCredential');

		
	}

	public function setPath($p){
		$this -> path = $p."/templates";
	}

	public function setNav(){
		TemplateEngine::aggregate('nav',$this -> path,'Credential.nav',30);
	}
}

?>