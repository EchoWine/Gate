<?php

class SystemInfoView extends View{
	

	public function __construct(){}
	
	public static function setNav(){
		Module::TemplateAggregate('admin/nav','admin/nav',99);
	}


	public static function setPage(){
		Module::TemplateOverwrite('admin/content','admin/page');
	}

}

?>