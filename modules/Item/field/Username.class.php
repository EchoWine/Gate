<?php

class Username extends _String{
		
	/**
	 * Is unique value
	 */
	public $unique = true;

	/**
	 * Min length value
	 */
	public $minLength = 1;
	
	/**
	 * Initialize label
	 */
	public function iniLabel(){
		$this -> label = 'Username';
	}
}
?>