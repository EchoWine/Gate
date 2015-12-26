<?php

class CredentialView extends ItemView{
	
	public $model;
	public $controller;
	public $path;
	
	public function setPath($p){
		$this -> path = $p."/templates";
	}

	public function setNav(){
		Module::TemplateAggregate('nav',$this -> path,'nav',30);
	}

	public function setPage(){

		if(true)
			Module::TemplateOverwrite('content','page');

	}
}

?>