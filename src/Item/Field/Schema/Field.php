<?php

namespace Item\Field\Schema;

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
	 * Edit
	 */
	public $edit = true;

	/**
	 * Get
	 */
	public $get = true;

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

			$response = new \Item\Response\Error(
				self::ERROR_INVALID_TOO_SHORT_CODE,
				sprintf(self::ERROR_INVALID_TOO_SHORT_MESSAGE,$this -> getLabel(),$this -> getMinLength())
			);

		}else if($length > $this -> getMaxLength()){

			$response = new \Item\Response\Error(
				self::ERROR_INVALID_TOO_LONG_CODE,
				sprintf(self::ERROR_INVALID_TOO_LONG_MESSAGE,$this -> getLabel(),$this -> getMaxLength())
			);

		}else if(!preg_match($this -> regex,$value)){

			$response = new \Item\Response\Error(
				self::ERROR_INVALID_CODE,
				sprintf(self::ERROR_INVALID_MESSAGE,$this -> getLabel())
			);

		}else{
			$response = new \Item\Response\Success();
		}

		$response -> setData(['value' => $value]);
		return $response;

	}

	/**
	 * Check if the field is enabled for add
	 */
	public function isAdd(){
		return $this -> add;
	}

	/**
	 * Check if the field is enabled for edit
	 */
	public function isEdit(){
		return $this -> edit;
	}

	/**
	 * Check if the field is enabled for get
	 */
	public function isGet(){
		return $this -> get;
	}

	/**
	 * Parse the value for data
	 */
	public function parseValue($value){
		return $value;
	}

	/**
	 * Parse the value for add
	 */
	public function parseValueAdd($value){
		return $this -> parseValue($value);
	}

	/**
	 * Parse the value for edit
	 */
	public function parseValueEdit($value){
		return $this -> parseValue($value);
	}

}
?>