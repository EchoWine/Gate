<?php

namespace Item\Credential;

class View extends \ItemView{
	
	public $navPos = 30;

	public function setTitle(){
		\Module::TemplateOverwrite('item.main.title','main.title');
	}

	public function setCat(){
		\Module::TemplateOverwrite('item.main.cat','main.cat');
	}

	// Overwrite page item
	/*
	public function setCat(){
		Module::TemplateOverwrite('item.main.cat','main.cat');
	}
	*/
}

?>