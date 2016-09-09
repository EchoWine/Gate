<?php

namespace CoreWine\ORM\Field\Field;

class Schema{
	
	/**
	 * Model
	 *
	 * @var string
	 */
	public $__model = null;

	/**
	 * Name
	 *
	 * @var bool
	 */
	public $name;

	/**
	 * Column
	 *
	 * @var bool
	 */
	public $column;

	/**
	 * Max length
	 *
	 * @var bool
	 */
	public $max_length = 255;

	/**
	 * Min length
	 *
	 * @var bool
	 */
	public $min_length = 0;

	/**
	 * Required
	 *
	 * @var bool
	 */
	public $required = false;

	/**
	 * Default
	 *
	 * @var bool
	 */
	public $default = NULL;

	/**
	 * Add
	 *
	 * @var bool
	 */
	public $add = true;

	/**
	 * Add if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in add/insert operation
	 */
	public $add_if_empty = false;

	/**
	 * Edit
	 *
	 * @var bool
	 */
	public $edit = true;

	/**
	 * Edit if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in edit/update operation
	 */
	public $edit_if_empty = false;

	/**
	 * Get
	 *
	 * @var bool
	 */
	public $get = true;

	/**
	 * Sort
	 *
	 * @var bool
	 */
	public $sort = true;

	/**
	 * Primary
	 *
	 * @var bool
	 */
	public $primary = false;

	/**
	 * Auto Increment
	 *
	 * @var bool
	 */
	public $auto_increment = false;

	/**
	 * Copy
	 *
	 * @var bool
	 */
	public $copy = true;

	/**
	 * Unique
	 *
	 * @var bool
	 */
	public $unique = false;

	/**
	 * Persist
	 *
	 * @var bool
	 */
	public $persist = true;

	/**
	 * Hidden
	 *
	 * @var bool
	 */
	public $hidden = false;

	/**
	 * Include field in toArray operations
	 *
	 * @var bool
	 */
	public $enable_to_array = true;

	/**
	 * Schema object
	 *
	 * @var Schema
	 */
	public $object_schema;

	/**
	 * Regex of field
	 */
	public $regex = "/^(.){0,255}$/iU";

	const VALIDATION_ERROR_REQUIRED = "error_required";
	const VALIDATION_ERROR_EMPTY = "error_empty";
	const VALIDATION_ERROR_TOO_SHORT = "error_too_short";
	const VALIDATION_ERROR_TOO_LONG = "error_too_long";
	const VALIDATION_ERROR_INVALID = "error_invalid";
	const VALIDATION_ERROR_NOT_UNIQUE = "error_not_unique";
	const VALIDATION_ERROR_INVALID_VALUE = "error_invalid_value";

	/**
	 * Construct
	 */
	public function __construct($name = null){
		if($name != null){
			$this -> name = $name;
			$this -> label = $name;
			$this -> column = $name;
		}
		return $this;
	}

	public function setObjectSchema($object_schema){
		$this -> object_schema = $object_schema;
	}

	public function getObjectSchema(){
		return $this -> object_schema;
	}


	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType(){
		$class = explode("\\",static::class);
		$class = $class[count($class) - 2];
		$class = lcfirst($class);
		$class = preg_replace("/([A-Z])/",'_$1',$class);
		$class = strtolower($class);
		return $class;
	}

	/**
	 * Is type
	 *
	 * @param string $class
	 *
	 * @return bool
	 */
	public function isType($type){
		$parents = array_map(function($class){
			$class = explode("\\",$class);
			return strtolower($class[count($class) - 2]);
		},array_merge([static::class],class_parents(static::class)));
		return in_array($type,$parents);
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

		throw new \Exception("Fatal error: Call to undefined method Model::{$method}()");
		
	}

	/**
	 * New
	 */
	public static function factory($name = null){
		return new static($name);
	}

	/**
	 * Set name
	 */
	public function name($name){
		$this -> name = $name;
		return $this;
	}

	/**
	 * Set primary
	 *
	 * @param bool
	 */
	public function primary($primary = true){
		$this -> primary = $primary;
	}

	/**
	 * Get primary
	 *
	 * @return bool
	 */
	public function getPrimary(){
		return $this -> primary;
	}

	/**
	 * Set hidden
	 *
	 * @param bool
	 */
	public function hidden($hidden = true){
		$this -> hidden = $hidden;
	}
	
	/**
	 * Get hidden
	 *
	 * @return bool
	 */
	public function getHidden(){
		return $this -> hidden;
	}

	/**
	 * Get enable to array
	 *
	 * @return bool
	 */
	public function getEnableToArray(){
		return $this -> enable_to_array;
	}

	/**
	 * Get auto increment
	 */
	public function getAutoIncrement(){
		return $this -> auto_increment;
	}

	/**
	 * Set auto Increment
	 */
	public function autoIncrement($auto_increment = true){
		$this -> auto_increment = $auto_increment;
	}

	/**
	 * Set default value
	 */
	public function default($default){
		$this -> default = $default;
		return $this;
	}

	/**
	 * Get default
	 * 
	 * @return mixed
	 */
	public function getDefault(){
		return $this -> default;
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
	public function maxLength($length){
		$this -> max_length = $length;
		return $this;
	}

	/**
	 * Set length
	 */
	public function minLength($length){
		$this -> min_length = $length;
		return $this;
	}

	/**
	 * Get length
	 */
	public function getMaxLength(){
		return $this -> max_length;
	}

	/**
	 * Get length
	 */
	public function getMinLength(){
		return $this -> min_length;
	}

	/**
	 * Set required
	 */
	public function required($required = true){
		$this -> required = $required;
		return $this;
	}

	/**
	 * Check if the field is enabled for add
	 *
	 * @return bool
	 */
	public function isAdd(){
		return $this -> add;
	}

	/**
	 * Set unique
	 *
	 * @param bool $unique
	 */
	public function unique($unique = true){
		$this -> unique = $unique;
		return $this;
	}

	/**
	 * Return if the field is unique
	 *
	 * @return bool
	 */
	public function getUnique(){
		return $this -> unique;
	}

	/**
	 * Return if the field is required
	 *
	 * @return bool
	 */
	public function getRequired(){
		return $this -> required;
	}

	/**
	 * Check if the field is enabled for edit
	 *
	 * @return bool
	 */
	public function isEdit(){
		return $this -> edit;
	}

	/**
	 * Check if the field is enabled for get
	 *
	 * @return bool
	 */
	public function isGet(){
		return $this -> get;
	}

	/**
	 * Check if the field is enabled for copy
	 *
	 * @return bool
	 */
	public function isCopy(){
		return $this -> copy;
	}

	/**
	 * Check if the field is enabled for sort
	 *
	 * @return bool
	 */
	public function isSort(){
		return $this -> sort;
	}

	/**
	 * Return a new istance of modelField
	 *
	 * @param mixed $value
	 * @return Model
	 */
	public function new($value = null){
		$class = explode("\\",static::class);
		unset($class[count($class) - 1]);
		$class = implode("\\",$class);

		$model = $this -> __model !== null ? $this -> __model : $class.'\Model';

		return new $model($this,$value);
	}

	/**
	 * Set DB schema
	 */
	public function alter($table){
		$col = $table -> string($this -> getColumn(),$this -> max_length);

		if(!$this -> required)
			$col -> null();
	}

	/**
	 * Check if the value is valid
	 */
	public function validate($value,$values,$model){
		if(is_object($value) || is_array($value))
			return null;

		if($this -> getRequired() && $value === null)
			return static::VALIDATION_ERROR_REQUIRED;

		$length = strlen($value);

		if($length < $this -> getMinLength())
			return static::VALIDATION_ERROR_TOO_SHORT;


		if($length > $this -> getMaxLength())
			return static::VALIDATION_ERROR_TOO_LONG;

		if(!preg_match($this -> regex,$value))
			return static::VALIDATION_ERROR_INVALID_VALUE;


		if($this -> getUnique()){

			$repository = $model::repository();

			if($model !== null && $model -> id !== null){
				$repository = $repository -> where($model -> getSchema() -> getPrimaryColumn(),"!=",$model -> getPrimaryField() -> getValue());

			}

			if($repository -> exists([$this -> getColumn() => $value])){
				return static::VALIDATION_ERROR_NOT_UNIQUE;
			}
		}

		return null;

	}

	/**
	 * Insert if value is empty
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function insertIfValueEmpty($value){
		return empty($value) ? $this -> add_if_empty : true;
	}

	/**
	 * Update if value is empty
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function updateIfValueEmpty($value){
		return empty($value) ? $this -> edit_if_empty : true;
	}

	/**
	 * Add schema field to model
	 *
	 * @param ORM\Schema
	 */
	public function addToModelSchema($schema){
		$schema -> setField($this -> getName(),$this);
	}

	/**
	 * To string
	 *
	 * @return string
	 */
	public function __tostring(){
		return static::class;
	}

	/**
	 * Add the field to query to find the model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function searchRepository($repository,$value,$table = null){

		if(!$table)
			$table = $this -> getObjectSchema() -> getTable();

		return $repository -> orWhereLike($table.".".$this -> getColumn(),'%'.$value.'%');
	}
}
?>