<?php

namespace Item\Field;

class Schema{
	
	/**
	 * Entity
	 */
	public $__entity = 'Item\Field\Entity';

	/**
	 * Name
	 */
	public $name;

	/**
	 * Column
	 */
	public $column;

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
		$this -> label = $name;
		$this -> column = $name;
		return $this;
	}

	/**
	 * Set name
	 */
	public function name($name){
		$this -> name = $name;
		return $this;
	}

	/**
	 * Get name
	 */
	public function getName(){
		return $this -> name;
	}

	/**
	 * Set label
	 */
	public function label($name){
		$this -> label = $name;
		return $this;
	}

	/**
	 * Get label
	 */
	public function getLabel(){
		return $this -> label;
	}

	/**
	 * Set column
	 */
	public function column($name){
		$this -> column = $name;
		return $this;
	}

	/**
	 * Get column
	 */
	public function getColumn(){
		return $this -> column;
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

	/**
	 * Return if the schema has an entity
	 *
	 * @return bool
	 */
	public function hasEntity(){
		return $this -> __entity !== null;
	}

	/**
	 * Return a new istance of entity
	 *
	 * @param mixed $value
	 * @return Entity
	 */
	public function newEntity($value){
		return new $this -> __entity($this,$value);
	}


}
?>