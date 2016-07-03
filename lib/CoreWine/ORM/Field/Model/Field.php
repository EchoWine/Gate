<?php

namespace CoreWine\ORM\Field\Model;

class Field{
	
	/**
	 * Schema
	 */
	public $schema;

	/**
	 * Model
	 */
	public $model;

	/**
	 * Value
	 */
	public $value;

	/**
	 * Value raw
	 */
	public $value_raw;

	/**
	 * Persist
	 */
	public $persist;

	/**
	 * Construct
	 */
	public function __construct($schema,$value){
		$this -> schema = $schema;
		$this -> value = $value;
		$this -> persist = $schema -> persist;
	}

	/**
	 * Set Model
	 */
	public function setModel($model){
		$this -> model = $model;
		$this -> iniAlias();
		$model -> setField($this -> getSchema() -> getName(),$this);
	}

	/**
	 * Initialize alias
	 */
	public function iniAlias(){
		$this -> alias = [$this -> getSchema() -> getName()];
	}

	/**
	 * Check if alias exists
	 */
	public function isAlias($alias){
		return in_array($alias,$this -> getAlias());
	}

	/**
	 * Get all alias
	 */
	public function getAlias(){
		return $this -> alias;
	}

	/**
	 * Return the model
	 */
	public function getModel(){
		return $this -> model;
	}

	/**
	 * Is primary
	 *
	 * @return bool
	 */
	public function isPrimary(){
		return $this -> getSchema() -> getPrimary();
	}

	/**
	 * Is unique
	 *
	 * @return bool
	 */
	public function isUnique(){
		return $this -> getSchema() -> getUnique();
	}

	/**
	 * Is Autoincrement
	 *
	 * @return bool
	 */
	public function isAutoIncrement(){
		return $this -> getSchema() -> getAutoIncrement();
	}

	/**
	 * Get the schema
	 *
	 * @return ORM\Field\Schema
	 */
	public function getSchema(){
		return $this -> schema;
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
	 * Set the value raw by repository
	 *
	 * @return mixed
	 */
	public function setValueRawToRepository($value_raw,$persist = false){

		$this -> value_raw = $value_raw;

		if(!$persist){
			$this -> setValue($this -> parseRawToValue($value_raw),false);
			$this -> persist = $persist;
		}
	}

	/**
	 * Set the value raw by repository
	 *
	 * @return mixed
	 */
	public function setValueRawFromRepository($value_raw,$persist = false,$relations = []){
		
		$value_raw = isset($value_raw[$this -> getSchema() -> getColumn()]) ? $value_raw[$this -> getSchema() -> getColumn()] : null;

		$this -> value_raw = $value_raw;

		if(!$persist){
			$this -> setValue($this -> parseRawToValue($value_raw),false);
			$this -> persist = $persist;
		}
	}

	/**
	 * Get the value raw
	 *
	 * @return mixed
	 */
	public function getValueRaw(){
		return $this -> value_raw;
	}

	/**
	 * Set the value
	 *
	 * @param mixed $value
	 */
	public function setValue($value = null,$persist = true){
		$this -> value = $value;

		if($persist){
			$this -> setValueRawToRepository($this -> parseValueToRaw($value),true);
			$this -> persist = $persist;
		}
	}

	/**
	 * Set value copied
	 *
	 * @param mixed $value
	 */
	public function setValueCopied($value = null){

		if(!$this -> getSchema() -> isCopy())
			return;

		if($this -> isUnique()){
			$i = 0;
			do{
				$value_copied = $this -> parseValueCopy($value,$i++);
				$res = $this -> getTable() -> getRepository() -> exists([$this -> getSchema() -> getColumn() => $value_copied]);
				
			}while($res);

			$value = $value_copied;
		}

		$this -> setValue($value);
	}

	/**
	 * Get the value
	 *
	 * @return mixed
	 */
	public function getValue(){
		return $this -> value;
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
	 * Parse the value from raw to value
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function parseRawToValue($value){
		return $value;
	}

	/**
	 * Parse the value from value to raw
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function parseValueToRaw($value){
		return $value;
	}

	/**
	 * To string
	 */
	public function __tostring(){
		return $this -> getValue();
	}

	/**
	 * Add the field to query to add an model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function addRepository($repository){
		return $repository -> addInsert($this -> getSchema() -> getColumn(),$this -> getValueRaw());
	}

	/**
	 * Add the field to query to edit an model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function editRepository($repository){
		return $repository -> addUpdate($this -> getSchema() -> getColumn(),$this -> getValueRaw());
	}

	/**
	 * Add the field to query to find the model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function whereRepository($repository){
		return $repository -> where($this -> getSchema() -> getColumn(),$this -> getValueRaw());
	}

}
?>