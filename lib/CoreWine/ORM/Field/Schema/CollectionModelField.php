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



}

?>