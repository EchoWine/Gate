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
	 * Is operation copy enabled
	 */
	public $copy = false;

	/**
	 * Is unique value
	 */
	public $unique = true;

	/**
	 * Initialization
	 */
	public function _ini(){

		if($this -> model -> orderByField == null){
			$this -> model -> orderByField = $this;
			$this -> model -> orderDirection = 'asc';
		}
	}

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
			'view' => $this -> label,
			'form' => null,
			'inputValue' => null,
		];
	}


}
?>