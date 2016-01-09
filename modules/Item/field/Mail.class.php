<?php
class Mail extends _String{
	
	/**
	 * Label
	 */
	public $label = 'E-Mail';

	/**
	 * Is unique value
	 */
	public $unique = true;

	/**
	 * Min length value
	 */
	public $minLength = 1;

	/**
	 * Initialize pattern
	 */
	public function iniPattern(){
		$this -> pattern = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/i';
	}

}
?>