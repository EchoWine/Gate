<?php

namespace Admin\View;

class View{

	/**
	 * Schema
	 *
	 * @var ORM\Schema
	 */
	public $schema;

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Construct
	 */
	public function __construct($schema){
		$this -> schema = $schema;
	}

	/**
	 * Get schema
	 *
	 * @return ORM\Schema
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	public function __call($method,$arguments){

		if($this -> getSchema() -> isField($method)){
			$builder = new ViewBuilder($this -> getSchema() -> getField($method),$arguments);
			$this -> fields[$method] = $builder;
			return $builder;
		}

		throw new Exceptions\UndefinedMethodException(static::class,$method);
	}

	/**
	 * Get all fields defined in view
	 *
	 * @return array
	 */
	public function getFields(){
		return $this -> fields;
	}

	/**
	 * Get all fields defined in view
	 *
	 * @return array
	 */
	public function isField($field){
		return isset($this -> fields[$field]);
	}
}
?>