<?php

namespace CoreWine\ORM\Field\Model;

class ModelField extends Field{

	/**
	 * Set Model
	 */
	public function setModel($model){
		$this -> model = $model;
		$model -> setField($this -> getSchema() -> getName(),$this);
		$model -> setField($this -> getSchema() -> getColumn(),$this);
	}

	/**
	 * Set the value raw by repository
	 *
	 * @return mixed
	 */
	public function setValueRawFromRepository($value_raw,$persist = false,$relations = []){
		
		$this -> value_raw = $value_raw;

		if(isset($relations[$this -> getSchema() -> getRelation()][$value_raw]))
			$value = $relations[$this -> getSchema() -> getRelation()][$value_raw];
		else
			$value = null;

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

		$value_raw = $value_raw -> getPrimaryField() -> getValue();
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

}
?>