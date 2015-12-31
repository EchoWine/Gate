<?php

class ID extends Field{
	
	/**
	 * Is operation add enabled
	 */
	public $add = false;
	
	/**
	 * Is operation edit enabled
	 */
	public $edit = false;

	public function iniLabel(){
		$this -> label = 'ID';
	}

}
?>