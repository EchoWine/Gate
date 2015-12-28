<?php

class ItemView extends View{
	
	public function setPageList(){
		Module::TemplateOverwrite('content','page-list');
	}

	public function setPageAdd(){
		Module::TemplateOverwrite('content','page-add');
	}

	public function setStyle(){
		Module::TemplateAggregate('style','style',1);
	}
}

?>