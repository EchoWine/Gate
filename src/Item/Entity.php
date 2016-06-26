<?php

namespace Item;

use Item\Response as Response;

class Entity{

	/**
	 * Item\Repository
	 */
	public static $__repository = 'Item\Repository';

	/**
	 * Item\Schema
	 */
	public static $__schema = 'Item\Schema';

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
	public $fields;

	/**
	 * Array of all values
	 */
	public $values;

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
			return $schema;

		$schema = static::$__schema;
		return new $schema();
	}

	/**
	 * Get static schema
	 */
	public static function repository(){
		$repository = static::$__repository;
		return new $repository(static::schema());
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



		return (object)static::repository() -> where('id',$ids[0]) -> first();
	}


}

?>