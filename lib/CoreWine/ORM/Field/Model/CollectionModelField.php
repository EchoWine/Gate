<?php

namespace CoreWine\ORM\Field\Model;

class CollectionModelField extends Field{

	/**
	 * has value raw
	 */
	public $has_value_raw = false;

	/** 
	 * List of all Model to save()
	 */
	public $value_to_save = [];

	/**
	 * Add a model to collection if isn't already added
	 *
	 * @param ORM\Model $model
	 */
	public function add($model){

		$model -> getFieldByColumn($this -> getSchema() -> getReference()) -> setValue($this -> getModel());
		$this -> addValue($model);
		$this -> addValueToSave($model);
	}

	/**
	 * Add model in value
	 *
	 * @param ORM\Model $model
	 */
	public function addValue($model){
		$this -> value[] = $model;
	}

	/**
	 * Remove model in value
	 *
	 * @param ORM\Model $model
	 */
	public function removeValue($index){
		unset($this -> value[$index]);
	}

	/**
	 * Add to model to save
	 *
	 * @param ORM\Model $model
	 */
	public function addValueToSave($model){
		$this -> value_to_save[$model -> getPrimaryField() -> getValue()] = $model;
	}

	/**
	 * Get list of all model to save
	 *
	 * @return array ORM\Model
	 */
	public function getValueToSave(){
		return $this -> value_to_save;
	}

	/**
	 * Remove a model to collection if exist
	 *
	 * @param ORM\Model $model
	 */
	public function remove($model){
		foreach($this -> getValue() as $n => $_model){
			if($model -> isEqual($_model)){
				$_model -> getFieldByColumn($this -> getSchema() -> getReference()) -> setValue(null);

				$this -> addValueToSave($model);
				$this -> removeValue($n);
			}
		}

	}

	/**
	 * Save all model in collection
	 */
	public function save(){
		foreach($this -> getValueToSave() as $value){
			$value -> save();
		}

	}

	/**
	 * Set the value raw by repository
	 *
	 * @return mixed
	 */
	public function setValueRawFromRepository($value_raw,$persist = false,$relations = []){
		
		$this -> value_raw = null;
		$value = [];

		if(isset($relations[$this -> getSchema() -> getRelation()])){
			foreach($relations[$this -> getSchema() -> getRelation()] as $result){

				foreach($result -> getFields() as $field){

					if($field -> getSchema() instanceof \CoreWine\ORM\Field\Schema\ModelField){

						if(!$this -> getModel() -> getPrimaryField()){
							print_r($this -> getModel());
							die('...');
						}

						# Of all results take only with a relation, with a column reference, with a value of primary == reference
						if(
							$field -> getSchema() -> getRelation() == $this -> getModel() && 
							$field -> getSchema() -> getColumn() == $this -> getSchema() -> getReference() &&
							$result -> getField($this -> getSchema() -> getReference()) -> getValueRaw() == 
							$this -> getModel() -> getPrimaryField() -> getValue()
						){
							
							$value[$result -> getPrimaryField() -> getValue()] = $result;
						}
					}
				}
			}
		}

		if(!$persist){
			
			$this -> setValue($this -> parseRawToValue($value),false);
			$this -> persist = $persist;
		}
	}

	/**
	 * Set the value raw
	 *
	 * @return mixed
	 */
	public function setValueRawToRepository($value_raw,$persist = false){

		$this -> value_raw = null;

		if(!$persist){
			$this -> setValue($this -> parseRawToValue($value_raw),false);
			$this -> persist = $persist;
		}
	}

	/**
	 * Add the field to query to add an model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function addRepository($repository){
		return $repository;
	}

	/**
	 * Add the field to query to edit an model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function editRepository($repository){
		return $repository;
	}

}
?>