<?php

namespace SystemInfo\Controller;

class SystemInfoController{
	
	public function __construct(){
		$this -> setNav();
		$this -> setPage();
	}

	public static function setNav(){
		\Module::TemplateAggregate('admin/nav','admin/nav',99);
	}


	public static function setPage(){
		\Module::TemplateOverwrite('admin/content','admin/page');
	}

}

?>