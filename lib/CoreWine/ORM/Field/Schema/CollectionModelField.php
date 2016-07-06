<?php

namespace CoreWine\ORM\Field\Schema;

class CollectionModelField extends IntegerField{
	
	/**
	 * Model
	 *
	 * @var
	 */
	public $__model = 'CoreWine\ORM\Field\Model\CollectionModelField';

	/**
	 * Name of model of Relation
	 *
	 * @var
	 */
	public $relation;

	/**
	 * Reference
	 *
	 * @var
	 */
	public $reference;

	/**
	 * Set relation
	 *
	 * @param String $relation
	 */
	public function relation($relation){
		$this -> relation = $relation;
		$this -> column = null;
		return $this;
	}

	/**
	 * Get relation
	 */
	public function getRelation(){
		return $this -> relation;
	}

	/**
	 * Set reference
	 *
	 * @param String $reference
	 */
	public function reference($reference){
		$this -> reference = $reference;
		return $this;
	}

	/**
	 * Get reference
	 */
	public function getReference(){
		return $this -> reference;
	}


	/**
	 * Alter
	 */
	public function alter($table){
		
	}

	/**
	 * Construct
	 */
	public function __construct($relation,$name = null,$reference = null){
		$this -> name = $name;
		$this -> label = $name;
		$this -> column = $name;
		$this -> relation($relation);
		$this -> reference($reference);
		return $this;
	}

	/**
	 * New
	 */
	public static function factory($relation,$name = null,$reference = null){
		return new static($relation,$name,$reference);
	}

}

?>