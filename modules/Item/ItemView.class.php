<?php

class ItemView extends View{
	
	public function setPageList(){
		Module::TemplateOverwrite('content','main-list');
	}

	public function setPageAdd(){
		Module::TemplateOverwrite('content','main-add');
	}

	public function setStyle(){
		Module::TemplateAggregate('style','style',1);
	}
}

?>