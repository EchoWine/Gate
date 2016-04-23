<?php

namespace Item\Schema;

class Item{

	/**
	 * Name of table
	 */
	public $table;

	/**
	 * Fields
	 */
	public $fields;

	/**
	 * Add a field
	 */
	public function field($class,$name){

		if(is_subclass_of($class,Field::class)){

			$field = new $class($name);
			$this -> fields[$name] = $field;
			return $field;
		}else{
			new \Exception('Error during creation of field');
		}
	}

	/**
	 * Construct
	 */
	public function __construct(){
		$this -> fields();
	}

	/**
	 * Set fields
	 */
	public function fields(){}

	/**
	 * Get fields
	 */
	public function getFields(){
		return $this -> fields;
	}

	/**
	 * Get table
	 */
	public function getTable(){
		return $this -> table;
	}
}
?>
