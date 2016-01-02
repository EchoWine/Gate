<?php
class Mail extends _String{
	
	/**
	 * Min length value
	 */
	public $minLength = 1;
	
	/**
	 * Initialize label
	 */
	public function iniLabel(){
		$this -> label = 'E-mail';
	}
	
	/**
	 * Initialize pattern
	 */
	public function iniPattern(){
		$this -> pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/i';
	}

}
?>