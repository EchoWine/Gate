<?php

class CredentialView extends View{
	
	public $model;
	public $controller;
	public $path;
	
	public function setPath($p){
		$this -> path = $p."/templates";
	}

	public function setNav(){
		TemplateEngine::aggregate('nav',$this -> path,'credential.nav',30);
	}

	public function setPage(){

		TemplateEngine::overwrite('content','credential.page','$pageCredential');

	}
}

?>