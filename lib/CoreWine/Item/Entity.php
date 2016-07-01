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
	 * Last validation
	 */
	public static $last_validate = [];

	/**
	 * Array of all fields
	 */
	public $fields = [];

	/**
	 * Persist
	 */
	public $persist = true;

	/**
	 * Construct
	 */
	public function __construct(){
		
		$this -> iniFields();
	}

	public function iniFields(){
		foreach(self::schema() -> getFields() as $name => $field){
			$entityField = $field -> newEntity();
			$entityField -> setTable($this);
			$this -> setField($name,$entityField);
		}
	}

	/**
	 * Get
	 *
	 * @param string $attribute
	 *
	 * @return mixed
	 */
	public function __get($attribute){
		
		if($this -> isField($attribute))
			return $this -> getField($attribute) -> getValue();
		

		return null;
		
	}

	/**
	 * Set
	 *
	 * @param string $attribute
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function __set($attribute,$value){
		
		if($this -> isField($attribute)){
			$this -> getField($attribute) -> setValue($value);
		}
		
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

		if($this -> isField($method))
			return $this -> getField($method);
		

		throw new \Exception("Fatal error: Call to undefined method Entity::{$method}()");
		
	}

	/**
	 * Set persist
	 *
	 * @return bool
	 */
	public function setPersist($persist = false){
		$this -> persist = $persist;
	}

	/**
	 * Get persist
	 *
	 * @return bool
	 */
	public function getPersist(){
		return $this -> persist;
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
	 * Set a field
	 *
	 * @param $name
	 * @param $field
	 */
	public function setField($name,$field){
		$this -> fields[$name] = $field;
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
	 * Is set a field
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isField($name){
		return isset($this -> fields[$name]);
	}

	/**
	 * Get a field
	 *
	 * @param string $name
	 *
	 * @return Field
	 */
	public function getField($name){
		return $this -> fields[$name];
	}


	/**
	 * Get the primary field
	 *
	 * @return Array
	 */
	public function getPrimaryFields(){

		$fields = [];
		foreach($this -> getFields() as $field){
			if($field -> isPrimary())
				$fields[] = $field;
		}

		return $fields;
	}

	/**
	 * Get the autoincrement field
	 *
	 * @return Array
	 */
	public function getAutoIncrementField(){

		foreach($this -> getFields() as $field){
			if($field -> isAutoIncrement())
				return $field;
		}

		return null;
	}


	public static function validateField($field,$value,$values,$entity){

		return  $field -> validate($value,$values,$entity,static::repository());

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

			if($schema -> isField($name)){

				if($response = static::validateField($schema -> getField($name),$value,$values,$entity)){
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
	public static function validateAll($values = [],$entity = null){

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
	public static function validateCreate($values = []){

		return static::validateAll($values,null);
	}

	/**
	 * Validate all values of schema for update
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public static function validateUpdate($values = [],$entity){

		return static::validateAll($values,$entity);
	}

	/**
	 * Set last validation response
	 *
	 * @param array $validation
	 */
	public static function setLastValidate($validate){
		static::$last_validate = $validate;
	}

	/**
	 * Get last validation response
	 *
	 * @param array $validation
	 */
	public static function getLastValidate(){
		return static::$last_validate;
	}

	/**
	 * Return a new entity and save
	 *
	 * @param array $values
	 *
	 * @return Entity
	 */
	public static function create($values = []){
		
		$entity = static::new($values);

		return $entity -> save() 
			? $entity 
			: false;

	}

	/**
	 * Return a new entity copied
	 *
	 * @param array $values
	 *
	 * @return Entity
	 */
	public static function copy($source){
		
		$entity = static::new();

		$entity -> fillFrom($source);


		return $entity -> save() 
			? $entity 
			: false;

	}


	/**
	 * Return a new entity
	 * 
	 * @param array $values
	 *
	 * @return Entity
	 */
	public static function new($values = []){

		$entity = new static();
		$entity -> fill($values);

		return $entity;
	}


	/**
	 * Fill entity with array
	 *
	 * @param array $result
	 */
	public function fill($values = []){

		foreach($values as $name => $value){
			$this -> {$name} = $value;
		}

		return $this;
	}

	/**
	 * Fill entity with array given by repository
	 *
	 * @param array $result
	 */
	public function fillRaw($values = []){

		foreach($values as $name => $value){
			if($this -> isField($name)){
				$this -> getField($name) -> setValueRaw($value);
			}
		}

		return $this;
	}

	/**
	 * Fill from another entity
	 *
	 * @param array $result
	 */
	public function fillFrom($entity){

		foreach($entity -> getFields() as $name => $field){
			if($this -> isField($name)){
				$this -> getField($name) -> setValueCopied($field -> getValue());
			}
		}

		return $this;
	}

	/**
	 * Get field to persist
	 *
	 * @return array
	 */
	public function getFieldsToPersist(){
		$fields = [];
		foreach($this -> getFields() as $name => $field){
			if($field -> getPersist()){
				$fields[$name] = $field;
			}
		}
		return $fields;
	}

	/**
	 * Return all values of given fields
	 *
	 * @return Array
	 */
	public static function getValues($fields){
		$values = [];

		foreach($fields as $name => $field){
			$values[$name] = $field -> getValue();
		}

		return $values;
	}

	/**
	 * Save the entity
	 */
	public function save(){

		$fields = $this -> getFieldsToPersist();
		$values = static::getValues($fields);

		$validation = static::validate($values);
		static::setLastValidate($validation);

		if(!empty($validation))
			return false;
		
		if($this -> getPersist()){
			$ai = $this -> insert($fields);

			if(($field = $this -> getAutoIncrementField()) !== null)
				$field -> setValueRaw($ai[0]);

		}else{
			$this -> update($fields);
		}

		$this -> setPersist(0);



		return $this;

	}

	protected function wherePrimary($repository){
		foreach($this -> getPrimaryFields() as $field){
			$repository = $field -> where($repository);
		}

		return $repository;
	}

	/**
	 * Update Entity
	 * 
	 * @param Array $fields
	 *
	 * @return bool
	 */
	public function update($fields){

		$repository = $this -> getRepository();

		$repository = $this -> wherePrimary($repository);

		foreach($fields as $name => $field){
			$repository = $field -> edit($repository);
		}

		return $repository -> update();
	}

	/**
	 * Insert Entity
	 * 
	 * @param Array $fields
	 *
	 * @return bool
	 */
	public function insert($fields){

		$repository = $this -> getRepository();

		foreach($fields as $name => $field){
			$repository = $field -> add($repository);
		}

		return $repository -> insert();

		
	}

	/**
	 * Alias where repository
	 */
	public static function where($v1 = null,$v2 = null,$v3 = null,$v4 = null){
		return static::repository() -> where($v1,$v2,$v3,$v4);
	}

	/**
	 * Delete
	 */
	public function delete(){

		if($this -> getPersist())
			return null;

		$this -> setPersist(1);

		$this -> wherePrimary($this -> getRepository()) -> delete();
	}

	/**
	 * To array
	 */
	public function toArray(){

		$schema = $this -> getSchema();

		$return = [];
		foreach($schema -> getFields() as $name => $field){

			$return[$name] = $this -> {$name};
		
		}

		return $return;
	}

}

?>