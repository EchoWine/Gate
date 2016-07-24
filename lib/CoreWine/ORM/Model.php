<?php

namespace CoreWine\ORM;

use CoreWine\ORM\Response as Response;
use CoreWine\Exceptions as Exceptions;

class Model{

	/**
	 * Table
	 *
	 * @var
	 */
	public static $table;

	/**
	 * ORM\Repository
	 */
	public static $__repository = 'CoreWine\ORM\Repository';

	/**
	 * ORM\Schema
	 */
	public static $__schema = 'CoreWine\ORM\Schema';

	/**
	 * Schema
	 */
	public static $schema = null;

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
	 * Callable to repository
	 *
	 * List of all callable to repository
	 *
	 * @var array
	 */
	public static $callable_to_repository = [
		'all','first','wherePrimary','where','count','firstByPrimary','orderBy','orderByDesc','orderByAsc'
	];

	/**
	 * Construct
	 */
	public function __construct(){
		
		$this -> iniFields();
	}

	public function iniFields(){
		foreach(static::schema() -> getFields() as $name => $field){
			$modelField = $field -> new();
			$modelField -> setModel($this);
			// $this -> setField($name,$modelField);
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
		
		if($this -> isField($attribute)){
			return $this -> getField($attribute) -> getValue();
		}
		

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
	public function __call($method,$arguments){

		if($this -> isField($method))
			return $this -> getField($method);

		throw new Exceptions\UndefinedMethodException(static::class,$method);
		
	}

	/**
	 * Clone 
	 */
	public function __clone(){
		$fields = [];
		foreach($this -> fields as $name => $field){
			$fields[$name] = clone $field;
		}

		$this -> fields = $fields;
	}

	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic($method,$arguments){

		if(in_array($method,static::$callable_to_repository) && is_callable(array(static::repository(),$method))){
			return call_user_func_array([static::repository(),$method],$arguments);
		}	

		throw new Exceptions\UndefinedMethodException(static::class,$method);
		
	}

	/**
	 * Define the fields schema
	 *
	 * @param Schema $schema
	 */
	public static function setSchemaFields($schema){}

	/**
	 * Seed
	 */
	public static function setSeed(){}

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

		# Ugly, tiny stuff
		$tmp = null;
		static::$schema = &$tmp;
		unset($tmp);

		$schema = new static::$__schema();
		$schema -> setTable(static::$table);

		static::$schema = $schema;
		static::setSchemaFields(new SchemaBuilder($schema));
		static::repository() -> alterSchema();
		static::setSeed();

		foreach(static::schema() -> getFields() as $field){
			$field -> setObjectSchema(static::schema());
		}

		return $schema;
	}

	/**
	 * Get static schema
	 */
	public static function repository(){
		$repository = static::$__repository;
		return new $repository(static::class);
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
	 * Get all fields and all alias
	 *
	 * @return array of fields
	 */
	public function getFieldsWithAlias(){
		$return = [];
		foreach($this -> getFields() as $field){
			foreach($field -> getAlias() as $alias){
				$return[$alias] = $field;
			}
		}
		return $return;
	}


	/**
	 * Is set a field
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isField($name){
		foreach($this -> fields as $field){
			if($field -> isAlias($name)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Get a field
	 *
	 * @param string $name
	 *
	 * @return Field
	 */
	public function getField($name){

		foreach($this -> fields as $field){
			if($field -> isAlias($name)){
				return $field;
			}
		}

		return null;
	}

	/**
	 * Get a field by column
	 *
	 * @param string $name
	 *
	 * @return Field
	 */
	public function getFieldByColumn($column){
		foreach($this -> getFields() as $field){
			if($field -> getSchema() -> getColumn() == $column)
				return $field;
		}

		return null;
	}



	/**
	 * Get the primary field
	 *
	 * @return Array
	 */
	public function getPrimaryField(){

		foreach($this -> getFields() as $field){
			if($field -> isPrimary())
				return $field;
		}

		return null;;
	}


	/**
	 * Get the primary field value
	 *
	 * @return mixed
	 */
	public function getPrimaryValue(){

		return $this -> getPrimaryField() -> getValue();
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


	public static function validateField($field,$value,$values,$model){
		return  $field -> validate($value,$values,$model);

	}

	/**
	 * Validate only values sent as param
	 *
	 * @param array $values
	 *
	 * @return array
	 */
	public static function validate($values,$model = null){

		$errors = []; 
		$schema = static::schema();

		$fields = $schema -> getFields();

		foreach($values as $name => $value){

			if($schema -> isField($name)){

				if($response = static::validateField($schema -> getField($name),$value,$values,$model)){
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
	public static function validateAll($values = [],$model = null){

		$errors = []; 

		$schema = static::schema();

		foreach($schema -> getFields() as $name => $field){

			$value = isset($values[$name]) ? $values[$name] : null;

			if($response = static::validateField($field,$value,$values,$model)){
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
	public static function validateUpdate($values = [],$model){

		return static::validateAll($values,$model);
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
	 * Return a new model and save
	 *
	 * @param array $values
	 *
	 * @return Model
	 */
	public static function create($values = []){
		
		$model = static::new($values);

		return $model -> save() 
			? $model 
			: false;

	}

	/**
	 * Return a new model copied
	 *
	 * @param array $values
	 *
	 * @return Model
	 */
	public static function copy($source){
		
		$model = static::new();

		$model -> fillFrom($source);


		return $model -> save() 
			? $model 
			: false;

	}

	/**
	 * Return a new model
	 * 
	 * @param array $values
	 *
	 * @return Model
	 */
	public static function new($values = []){

		$model = new static();
		$model -> fill($values);

		return $model;
	}

	/**
	 * Fill model with array
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
	 * Fill model with array given by repository
	 *
	 * @param array $values
	 * @param array $relations
	 */
	public function fillRawFromRepository($values = [],$relations = []){

		foreach($this -> getFields() as $name => $field){
			$this -> getField($name) -> setValueRawFromRepository($values,false,$relations);
		}

		return $this;
	}

	/**
	 * Fill from another model
	 *
	 * @param array $result
	 */
	public function fillFrom($model){

		foreach($model -> getFields() as $name => $field){
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
	 * Return all values of given fields
	 *
	 * @return Array
	 */
	public static function getValuesInsert($fields){
		$values = [];

		foreach($fields as $name => $field){
			if($field -> getSchema() -> insertIfValueEmpty($field -> getValue()))
				$values[$name] = $field -> getValue();
		}

		return $values;
	}

	/**
	 * Return all values of given fields
	 *
	 * @return Array
	 */
	public static function getValuesUpdate($fields){
		$values = [];

		foreach($fields as $name => $field){
			if($field -> getSchema() -> updateIfValueEmpty($field -> getValue())){
				$values[$name] = $field -> getValue();
			}

		}

		return $values;
	}


	/**
	 * Return all values of all fields
	 *
	 * @return Array
	 */
	public function getAllValuesRaw(){
		$values = [];

		foreach($this -> getFields() as $name => $field){
			if($field -> hasValueRaw())
				$values[$name] = $field -> getValueRaw();
		}

		return $values;
	}

	/**
	 * Save the model
	 */
	public function save(){

		$fields = $this -> getFieldsToPersist();

		if($this -> getPersist()){
			$values = $this -> getValuesInsert($fields);
		}else{
			$values = $this -> getValuesUpdate($fields);

		}


		$validation = static::validate($values,$this);
		static::setLastValidate($validation);

		if(!empty($validation))
			return false;

		if($this -> getPersist()){

			$ai = $this -> insert($fields);

			if(($field = $this -> getAutoIncrementField()) !== null)
				$field -> setValueRawFromRepository([$field -> getSchema() -> getColumn() => $ai[0]]);

		}else{
			$this -> update($fields);
		}

		$this -> setPersist(0);



		return $this;

	}

	/**
	 * Get repository where primary
	 *
	 * @param $repository
	 *
	 * @return Repository
	 */
	protected function wherePrimaryByRepository($repository){
		return $this -> getPrimaryField() -> whereRepository($repository);
	}

	/**
	 * Update Model
	 * 
	 * @param Array $fields
	 *
	 * @return bool
	 */
	public function update($fields){

		$repository = $this -> getRepository();

		$repository = $this -> wherePrimaryByRepository($repository);

		foreach($fields as $name => $field){
			$repository = $field -> editRepository($repository);
		}

		return $repository -> update();
	}

	/**
	 * Insert Model
	 * 
	 * @param Array $fields
	 *
	 * @return bool
	 */
	public function insert($fields){

		$repository = $this -> getRepository();

		foreach($fields as $name => $field){
			$repository = $field -> addRepository($repository);
		}

		return $repository -> insert();

		
	}

	/**
	 * Delete
	 */
	public function delete(){

		if($this -> getPersist())
			return null;

		$this -> setPersist(1);

		$this -> wherePrimaryByRepository($this -> getRepository()) -> delete();
	}

	/**
	 * To array
	 */
	public function toArray(){

		$values = [];

		foreach($this -> getFields() as $name => $field){
			if($field -> hasValueRaw())
				if($field -> getSchema() -> getEnableToArray())
					$values[$field -> getNameToArray()] = $field -> getValueToArray();
		}

		return $values;
	}

	/**
	 * Truncate the table
	 *
	 * @return Result
	 */
	public static function truncate(){
		return static::repository() -> truncate();
	}

	/**
	 * To string
	 */
	public function __tostring(){
		return static::class.": ".json_encode($this -> toArray())."\n";
	}

	/**
	 * Return if current model is equal 
	 */
	public function isEqual($model){
		return $this -> getPrimaryField() -> getValue() == $model -> getPrimaryField() -> getValue();
	}

}

?>