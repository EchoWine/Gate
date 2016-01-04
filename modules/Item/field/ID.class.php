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
	 * Basic pattern
	 */
	public $_pattern = "[0-9]";

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
			'form' => $this -> label,
			'inputValue' => null,
		];
	}

	/**
	 * Add the field to the query 'search'
	 * @param $q (object) query builder
	 * @param $v (mixed) value searched
	 * @return (object) query builder
	 */
	public function search($q,$v){
		return $q -> orWhere($this -> getColumnName(),$v);
	}


}
?>