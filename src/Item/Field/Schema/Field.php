<?php

namespace Item\Field\Schema;

use Item\Response as Response;

class Field{
	
	/**
	 * Entity
	 */
	public $__entity = 'Item\Field\Entity';

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
	public $maxLength = 80;

	/**
	 * Lenght
	 */
	public $minLength = 0;

	/**
	 * Required
	 */
	public $required = false;

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
	 * Sort
	 */
	public $copy = true;

	/**
	 * Sort
	 */
	public $unique = false;

	/**
	 * Regex of field
	 */
	public $regex = "/^(.){0,80}$/iU";

	const ERROR_INVALID_CODE = 'field_invalid_value';
	const ERROR_INVALID_MESSAGE = '%s is invalid';
	const ERROR_INVALID_TOO_SHORT_CODE = 'field_invalid_too_short';
	const ERROR_INVALID_TOO_SHORT_MESSAGE = '%s is too short (min: %s)';
	const ERROR_INVALID_TOO_LONG_CODE = 'field_invalid_too_long';
	const ERROR_INVALID_TOO_LONG_MESSAGE = '%s is too long (max: %s)';

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
	public function required($required){
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
	public function newEntity($value){
		return new $this -> __entity($this,$value);
	}

	/**
	 * Check if the value is valid
	 */
	public function isValid($value){

		$length = strlen($value);

		if($length < $this -> getMinLength()){

			$response = new Response\ApiFieldErrorTooShort($this -> getLabel(),$this -> getMinLength());

		}else if($length > $this -> getMaxLength()){

			$response = new Response\ApiFieldErrorTooLong($this -> getLabel(),$this -> getMaxLength());


		}else if(!preg_match($this -> regex,$value)){

			$response = new Response\ApiFieldErrorInvalid($this -> getLabel(),$value);

		}else{
			$response = new Response\Success();
		}

		$response -> setData(['value' => $value]);
		return $response;

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
	 * Check if the field is unique
	 *
	 * @return bool
	 */
	public function isUnique(){
		return $this -> unique;
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
	 * Parse the value for data
	 *
	 * @param mixed $value
	 *
	 * @return value parsed
	 */
	public function parseValue($value){
		return $value;
	}

	/**
	 * Parse the value for add
	 *
	 * @param mixed $value
	 *
	 * @return value parsed
	 */
	public function parseValueAdd($value){
		return $this -> parseValue($value);
	}

	/**
	 * Parse the value for edit
	 *
	 * @param mixed $value
	 *
	 * @return value parsed
	 */
	public function parseValueEdit($value){
		return $this -> parseValue($value);
	}

	/**
	 * Parse the value for edit
	 *
	 * @param mixed $value
	 * @param int $i count
	 *
	 * @return value parsed
	 */
	public function parseValueCopy($value,$i){
		return $value."_".$i;
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