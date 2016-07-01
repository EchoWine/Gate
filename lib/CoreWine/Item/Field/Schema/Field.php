<?php

namespace CoreWine\Item\Field\Schema;

use CoreWine\Item\Response as Response;

class Field{
	
	/**
	 * Entity
	 */
	public $__entity = 'CoreWine\Item\Field\Entity';

	/**
	 * Name
	 */
	public $name;

	/**
	 * Column
	 */
	public $column;

	/**
	 * Lenght
	 */
	public $maxLength = 255;

	/**
	 * Lenght
	 */
	public $minLength = 0;

	/**
	 * Required
	 */
	public $required = false;

	/**
	 * Default
	 */
	public $default = NULL;

	/**
	 * Add
	 */
	public $add = true;

	/**
	 * Add if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in add/insert operation
	 */
	public $addIfEmpty = true;

	/**
	 * Edit
	 */
	public $edit = true;

	/**
	 * Edit if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in edit/update operation
	 */
	public $editIfEmpty = true;

	/**
	 * Get
	 */
	public $get = true;

	/**
	 * Sort
	 */
	public $sort = true;

	/**
	 * Primary
	 */
	public $primary = false;

	/**
	 * Auto Increment
	 */
	public $auto_increment = false;

	/**
	 * Copy
	 */
	public $copy = true;

	/**
	 * Unique
	 */
	public $unique = false;

	/**
	 * Persist
	 */
	public $persist = true;

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

	/**
	 * Construct
	 */
	public function __construct($name){
		$this -> name = $name;
		$this -> label = $name;
		$this -> column = $name;
		return $this;
	}

	/**
	 * Set name
	 */
	public function name($name){
		$this -> name = $name;
		return $this;
	}

	/**
	 * Get primary
	 */
	public function getPrimary(){
		return $this -> primary;
	}

	/**
	 * Set primary
	 */
	public function primary($primary = true){
		$this -> primary = $primary;
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
		$this -> maxLength = $length;
		return $this;
	}

	/**
	 * Set length
	 */
	public function minLength($length){
		$this -> minLength = $length;
		return $this;
	}

	/**
	 * Get length
	 */
	public function getMaxLength(){
		return $this -> maxLength;
	}

	/**
	 * Get length
	 */
	public function getMinLength(){
		return $this -> minLength;
	}

	/**
	 * Set required
	 */
	public function required($required = true){
		$this -> required = $required;
		return $this;
	}

	/**
	 * Set DB schema
	 */
	public function alter($table){
		$col = $table -> string($this -> name,$this -> maxLength);

		if(!$this -> required)
			$col -> null();
	}

	/**
	 * Return if the schema has an entity
	 *
	 * @return bool
	 */
	public function hasEntity(){
		return $this -> __entity !== null;
	}

	/**
	 * Return a new istance of entity
	 *
	 * @param mixed $value
	 * @return Entity
	 */
	public function newEntity($value = null){
		return new $this -> __entity($this,$value);
	}


	/**
	 * Check if the value is valid
	 */
	public function validate($value,$values,$entity,$repository){

		if($this -> getRequired() && $value == null)
			return static::VALIDATION_ERROR_REQUIRED;

		$length = strlen($value);

		if($length < $this -> getMinLength())
			return static::VALIDATION_ERROR_TOO_SHORT;


		if($length > $this -> getMaxLength())
			return static::VALIDATION_ERROR_TOO_LONG;

		if(!preg_match($this -> regex,$value))
			return static::VALIDATION_ERROR_INVALID_VALUE;


		if($this -> getUnique()){

			if($entity !== null && $entity -> id !== null)
				$repository = $repository -> where($this -> getColumn(),'!=',$entity -> {$this -> getName()});

			if($repository -> exists([$this -> getColumn() => $value])){
				return static::VALIDATION_ERROR_NOT_UNIQUE;
			}
		}

		return null;

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
	 * Check if the field is needed for edit
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isAddNeeded($value){
		return empty($value) ? $this -> addIfEmpty : true;
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
	 * Check if the field is needed for edit
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function isEditNeeded($value){
		return empty($value) ? $this -> editIfEmpty : true;
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
	 * Add
	 *
	 * @param Repository $repository
	 * @param mixed $value
	 *
	 * @return value parsed
	 */
	public function add($value){
		$this -> getEntity() -> {$this -> getName()} = $value;
	}

	/**
	 * Edit
	 *
	 * @param Repository $repository
	 * @param mixed $value
	 *
	 * @return value parsed
	 */
	public function edit($value){
		$entity -> {$this -> getName()} = $value;
	}

	/**
	 * Set
	 *
	 * @param Repository $repository
	 * @param mixed $value
	 *
	 * @return value parsed
	 */
	public function set($value){
		$entity -> {$this -> getName()} = $value;
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

		throw new \Exception("Fatal error: Call to undefined method Entity::{$method}()");
		
	}

}
?>