<?php

class AuthView extends View{

	/**
	 * Set the login page
	 */
	public function setLogin(){

		if(!$this -> controller -> logged)
			Module::TemplateOverwrite('main','login');
		
	}

	/**
	 * Set the header
	 */
	public function setHeader(){
		Module::TemplateAggregate('header-nav','header-nav',30);
	}
}

?>