<?php

class ID extends Field{
	
	/**
	 * Is operation add enabled
	 */
	public $add = false;

	public function iniLabel(){
		$this -> label = 'ID';
	}

}
?>