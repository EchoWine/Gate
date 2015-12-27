<?php

class CredentialView extends ItemView{
	
	public $model;
	public $controller;
	
	public function setNav(){
		Module::TemplateAggregate('nav','nav',30);
	}

	public function setPage(){

		if(true)
			Module::TemplateOverwrite('content','page');

	}
}

?>