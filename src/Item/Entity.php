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

	public function getFields(){
		return $this -> fields;
	}

	public function getValues(){
		return $this -> values;
	}
	
	public function __call($method, $arguments){

		echo $method;

		if(isset($this -> fields[$method]))
			return $this -> fields[$method];
		

		throw new \Exception("Fatal error: Call to undefined method Entity::{$method}()");
		
	}

}

?>
