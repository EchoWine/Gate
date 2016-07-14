<?php

namespace CoreWine\ORM\Field\Model;

use CoreWine\ORM\Field\Field\Schema as FieldSchema;

class Schema extends FieldSchema{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\Model\Model';

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

	/**
	 * Construct
	 */
	public function __construct($relation = null,$name = null){
		$this -> name = $name;
		$this -> label = $name;
		$this -> column = $name;
		$this -> relation($relation);
		return $this;
	}

	/**
	 * New
	 */
	public static function factory($relation = null,$name = null){
		return new static($relation,$name);
	}

}

?>