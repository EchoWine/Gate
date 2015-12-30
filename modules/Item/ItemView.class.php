<?php

class ItemView extends View{
	
	/** 
	 * Initialization
	 */
	public function ini(){
		$this -> setStyle();
		$this -> setScript();
	}
	
	public function setStyle(){
		Module::TemplateAggregate('style','style',1);
	}

	public function setScript(){
		Module::TemplateAggregate('script','script',1);
	}

	public function setPageList(){
		Module::TemplateOverwrite('content','main-list');
	}

	public function setPageAdd(){
		Module::TemplateOverwrite('content','main-add');
	}

}

?>