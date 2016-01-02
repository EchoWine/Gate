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

	/**
	 * Initialize label
	 */
	public function iniLabel(){
		$this -> label = 'ID';
	}
	
	/**
	 * Initialize print
	 */
	public function iniPrint(){
		$this -> print = (object)[
			'list' => $this -> label,
			'get' => $this -> label,
			'form' => null,
			'value' => null,
		];
	}


}
?>