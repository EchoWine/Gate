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
}
?>