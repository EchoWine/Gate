<?php

class ItemView extends View{
	
	public function setPage(){
		$this -> setStyle();
		Module::TemplateOverwrite('content','page');
	}

	public function setStyle(){
		Module::TemplateAggregate('style','style',1);
	}
}

?>