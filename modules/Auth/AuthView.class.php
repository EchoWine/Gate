<?php

class AuthView extends View{

	/**
	 * Set the login page
	 */
	public function setLogin(){

		if(!$this -> controller -> logged)
			Module::TemplateOverwrite('admin','login');
		
	}

	/**
	 * Set the header
	 */
	public function setHeader(){
		Module::TemplateAggregate('admin/header-nav','admin/header-nav',30);
	}
}

?>