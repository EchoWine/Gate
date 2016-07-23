<?php

namespace Admin\View;

use CoreWine\Exceptions as Exceptions;

class ViewBuilder{

	/**
	 * Schema
	 *
	 * @var ORM\Schema
	 */
	public $schema;

	/**
	 * Display a select
	 *
	 * @var bool
	 */
	public $select = false;

	/**
	 * Display a input
	 *
	 * @var bool
	 */
	public $input = false;

	/**
	 * Relations
	 *
	 * @var array
	 */
	public $relations = [];

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
		if($this -> getSchema() -> getType() == "model"){
			if($this -> getSchema() -> getRelation()::schema() -> isField($method)){

				$field = $this -> getSchema() -> getRelation()::schema() -> getField($method);
				$this -> schema = $field;
				$this -> relations[] = $field;
				return $this;
			}
		}


		throw new Exceptions\UndefinedMethodException(static::class,$method);
	}

	/**
	 * Display a select
	 */
	public function select(){
		$this -> select = true;
	}

	/**
	 * Display a input
	 */
	public function input(){
		$this -> input = true;
	}

	/**
	 * Get relations
	 *
	 * @return array
	 */
	public function getRelations(){
		return $this -> relations;
	}

}
?>