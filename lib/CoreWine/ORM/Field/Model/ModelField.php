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
	public function setValueRawFromRepository($value_raw,$persist = false){
		$value_raw = $this -> getSchema() -> getRelation()::repository() -> firstByPrimary($value_raw);

		$this -> value_raw = $value_raw;

		if(!$persist){
			$this -> setValue($this -> parseRawToValue($value_raw),false);
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