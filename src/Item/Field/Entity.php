<?php

namespace Item\Field;

class Entity{
	
	/**
	 * Schema
	 */
	public $schema;

	/**
	 * Value
	 */
	public $value;

	/**
	 * Construct
	 */
	public function __construct($schema,$value){
		$this -> schema = $schema;
		$this -> value = $value;
	}

	/**
	 * Get the schema
	 *
	 * @return Item\Field\Schema
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Get the value
	 *
	 * @return mixed
	 */
	public function getValue(){
		return $this -> value;
	}
}
?>