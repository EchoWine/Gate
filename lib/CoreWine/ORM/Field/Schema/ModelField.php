<?php

namespace CoreWine\ORM\Field\Schema;

class ModelField extends Field{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\Model\ModelField';

	/**
	 * Name of model of Relation
	 */
	public $relation;

	/**
	 * Set relation
	 *
	 * @param String $relation
	 */
	public function relation($relation){
		$this -> relation = $relation;
		$this -> column = $this -> getName()."_".$this -> getRelation()::schema() -> getPrimaryField() -> getColumn();
		return $this;
	}

	/**
	 * Get relation
	 */
	public function getRelation(){
		return $this -> relation;
	}

}

?>