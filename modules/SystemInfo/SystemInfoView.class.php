<?php

class SystemInfoView extends View{
	

	public function __construct(){}
	public function setNav(){
		Module::TemplateAggregate('nav','nav',99);
	}


	public function setPage(){
		Module::TemplateOverwrite('content','page');
	}

}

?>