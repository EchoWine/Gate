<?php

namespace Item\Schema;

class Field{
	
	/**
	 * Name
	 */
	public $name;

	/**
	 * Lenght
	 */
	public $length = 80;

	/**
	 * Required
	 */
	public $required = false;

	/**
	 * Construct
	 */
	public function __construct($name){
		$this -> name = $name;
	}

	/**
	 * Set name
	 */
	public function name($name){
		$this -> name = $name;
		return $this;
	}

	/**
	 * Set length
	 */
	public function length($length){
		$this -> length = $length;
		return $this;
	}

	/**
	 * Set required
	 */
	public function required($required){
		$this -> required = $required;
		return $this;
	}

	/**
	 * Set DB schema
	 */
	public function alter($table){
		$col = $table -> string($this -> name,$this -> length);

		if(!$this -> required)
			$col -> null();
	}


}
?>