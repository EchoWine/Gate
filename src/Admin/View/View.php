<?php

namespace Admin\View;

use CoreWine\Exceptions;

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
	public function isField($field_name){
		foreach($this -> getFields() as $field){
			if($field_name == $field -> getName())
				return true;
		}

		return false;
	}

	/**
	 * Return an array with basic information of relations of all fields
	 *
	 * @return array
	 */
	public function getMinimalRelation(){
		$return = [];
		foreach($this -> getFields() as $field){
			$partials = [];
			foreach($field -> getRelations() as $n => $relation){
				if($field -> getUrl($n)){
					$partial = [];
					$partial['name'] = $relation -> getName();
					$partial['url'] = $field -> getUrl($n);

					switch($relation -> getType()){
						case "to_one":
							$partial['type'] = 'toOne';
							$partial['column'] = $relation -> getColumn();
						break;
						case "to_many":
							$partial['type'] = 'toMany';
							$partial['column'] = $relation -> getReference();
						break;
					}

					$partials[] = $partial;
				}
			}
			if(!empty($partials))
				$return[$field -> getName()] = $partials;
		}
		return $return;
	}
}
?>