<?php

namespace CoreWine\ORM\Field\Model;

class CollectionModelField extends Field{

	/**
	 * Set the value raw by repository
	 *
	 * @return mixed
	 */
	public function setValueRawFromRepository($value_raw,$persist = false,$relations = []){
		
		$this -> value_raw = null;
		$value = [];

		foreach($relations[$this -> getSchema() -> getRelation()] as $result){

			foreach($result -> getFields() as $field){

				if($field -> getSchema() instanceof \CoreWine\ORM\Field\Schema\ModelField){


					# Of all results take only with a relation, with a column reference, with a value of primary == reference
					if(
						$field -> getSchema() -> getRelation() == $this -> getModel() && 
						$field -> getSchema() -> getColumn() == $this -> getSchema() -> getReference() &&
						$result -> getField($this -> getSchema() -> getReference()) -> getValueRaw() == 
						$this -> getModel() -> getPrimaryField() -> getValue()
					){
						
						$value[] = $result;
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
	public function add($repository){
		return $repository;
	}

	/**
	 * Add the field to query to edit an model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function edit($repository){
		return $repository;
	}

}
?>