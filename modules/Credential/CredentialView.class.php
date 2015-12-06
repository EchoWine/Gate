<?php

class CredentialView extends View{
	
	public $model;
	public $controller;
	
	public function template($path){

		$path = $path."/templates";
		TemplateEngine::overwrite('content','Credential.page','$pageCredential');

		TemplateEngine::aggregate('nav',$path,'Credential.nav',30);

		// TemplateEngine::aggregate('style',$path,'auth.style');
		
	}
}

?>