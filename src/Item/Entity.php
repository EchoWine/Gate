<?php

namespace Item;

class Entity{

	/**
	 * Schema
	 */
	public $schema;

	/**
	 * Array of all fields
	 */
	public $fields;

	/**
	 * Array of all values
	 */
	public $values;

	/**
	 * Construct
	 *
	 * @param Schema $schema
	 */
	public function __construct(Schema $schema){
		$this -> schema = $schema;
	}

	/**
	 * Set fields using an array
	 *
	 * @param array $result
	 */
	public function setFieldsByArray($result){

		foreach($this -> schema -> getFields() as $fieldSchema){
			$value = $result[$fieldSchema -> getColumn()];
			$entity = $fieldSchema -> newEntity($value);

			$this -> fields[$fieldSchema -> getName()] = $entity;
			$this -> values[] = $value;
			$this -> {$fieldSchema -> getName()} = $value;
		}

		return $this;
	}

	/**
	 * Get all fields
	 *
	 * @return array of fields
	 */
	public function getFields(){
		return $this -> fields;
	}

	/**
	 * Get all values
	 *
	 * @return array of values
	 */
	public function getValues(){
		return $this -> values;
	}
	
	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function __call($method, $arguments){

		if(isset($this -> fields[$method]))
			return $this -> fields[$method];
		

		throw new \Exception("Fatal error: Call to undefined method Entity::{$method}()");
		
	}

}

?>