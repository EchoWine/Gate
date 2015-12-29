<?php

class CredentialView extends ItemView{
	
	public function setNav(){
		Module::TemplateAggregate('nav','nav',30);
	}

	public function setTitle(){
		Module::TemplateOverwrite('item.main.title','main.title');
	}

	public function setCat(){
		Module::TemplateOverwrite('item.main.cat','main.cat');
	}
}

?>