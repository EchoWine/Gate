<?php

namespace CoreWine\Item;

use CoreWine\Item\Response as Response;

class Entity{

	/**
	 * Item\Repository
	 */
	public static $__repository = 'CoreWine\Item\Repository';

	/**
	 * Item\Schema
	 */
	public static $__schema = 'CoreWine\Item\Schema';

	/**
	 * Schema
	 */
	public static $schema = null;

	/**
	 * Repositorya
	 */
	public static $repository = null;

	/**
	 * Array of all fields
	 */
	public $fields = [];

	/**
	 * Array of all values
	 */
	public $values = [];

	/**
	 * Construct
	 */
	public function __construct(){

	}

	/**
	 * Get static schema
	 */
	public static function schema(){

		if(static::$schema !== null)
			return static::$schema;

		$schema = static::$__schema;
		$schema = new $schema();
		static::__fields($schema);
		$schema -> setTable(static::$__table);
		static::$schema = $schema;
		static::repository() -> __alterSchema();
		return $schema;
	}

	/**
	 * Get static schema
	 */
	public static function repository(){
		$repository = static::$__repository;
		return new $repository(get_called_class());
	}


	/**
	 * Get schema
	 *
	 * @return Schema
	 */
	public function getSchema(){
		return static::schema();
	}

	/**
	 * Get repository
	 *
	 * @return Repository
	 */
	public function getRepository(){
		return static::repository();
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

	public static function validateField($field,$value,$values,$entity = null){

		$response = $field -> isValid($value,$values);

		if(!Response\Response::isResponseSuccess($response)){
			return $response;
		}

		if(Response\Response::isResponseSuccess($response)){
			if($field -> isUnique()){

				$repository = static::repository();


				if($entity !== null && $entity -> id !== null)
					$repository = $repository -> where('id','!=',$entity -> id);

				if($repository -> exists([$field -> getColumn() => $value])){
					return new Response\ApiFieldErrorNotUnique($field -> getLabel(),$value);
				}
			}
		}

		return null;

	}

	/**
	 * Validate only values sent as param
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public static function validate($values,$entity = null){

		$errors = []; 

		$schema = static::schema();

		$fields = $schema -> getFields();

		foreach($values as $name => $value){

			if(isset($fields[$name])){

				if($response = static::validateField($field,$value,$values,$entity)){
					$errors[$name] = $response;
				}


			}

		}

		return $errors;
	}


	/**
	 * Validate all values of schema
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public static function validateAll($values,$entity = null){

		$errors = []; 

		$schema = static::schema();

		foreach($schema -> getFields() as $name => $field){

			$value = isset($values[$name]) ? $values[$name] : null;

			if($response = static::validateField($field,$value,$values,$entity)){
				$errors[$name] = $response;
			}

		}

		return $errors;
	}

	/**
	 * Validate all values of schema for creation
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public static function validateCreate($values){

		return static::validateAll($values,null);
	}

	/**
	 * Validate all values of schema for update
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public static function validateUpdate($values,$entity){

		return static::validateAll($values,$entity);
	}


	/**
	 * Create element
	 *
	 * @return array
	 */
	public static function create($values){

		
		$schema = static::schema();

		$repository = static::repository();

		$entity = new static();

		foreach($schema -> getFields() as $name => $field){

			if($field -> isAdd()){

				$value = isset($values[$name]) ? $values[$name] : null;

				if($field -> isAddNeeded($value)){

					$field -> add($repository,$value,$entity);

				}

			}
		}

		$ids = $repository -> insert();

		$entity = static::repository() -> where('id',$ids[0]) -> first();


		return $entity;
	}

	/**
	 * Update elements
	 *
	 * @return array
	 */
	public function update($values){

		
		$schema = $this -> getSchema();

		$repository = $this -> getRepository();

		foreach($schema -> getFields() as $name => $field){

			if($field -> isEdit()){

				$value = isset($values[$name]) ? $values[$name] : null;

				if($field -> isEditNeeded($value)){

					$field -> edit($repository,$value,$this);

				}

			}
		}

		$repository -> where('id',$this -> id) -> update();

		return $this;
	}

	public static function where($v1 = null,$v2 = null,$v3 = null,$v4 = null){
		return static::repository() -> where($v1,$v2,$v3,$v4);
	}

	public function toArray(){

		$schema = $this -> getSchema();

		$return = [];
		foreach($schema -> getFields() as $name => $field){

			$return[$name] = $this -> {$name};
		
		}

		return $return;
	}
	
	/**
	 * Create a new entity and set fields using an array
	 *
	 * @param array $result
	 */
	public static function new($result){

		$entity = new static();
		$entity -> fill($result);
		return $entity;
	}

	/**
	 * Fill entity with array
	 *
	 * @param array $result
	 */
	public function fill($result){

		foreach($this -> getSchema() -> getFields() as $fieldSchema){
			if(isset($result[$fieldSchema -> getColumn()])){
				$value = $result[$fieldSchema -> getColumn()];
				$entity = $fieldSchema -> newEntity($value);

				$this -> fields[$fieldSchema -> getName()] = $entity;
				$this -> values[] = $value;
				$this -> {$fieldSchema -> getName()} = $value;
			}
		}

		return $this;
	}

}

?>