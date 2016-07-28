<?php

namespace CoreWine\ORM\Field\ID;

use CoreWine\ORM\Field\Integer\Schema as IntegerSchema;

class Schema extends IntegerSchema{

	public $name = 'id';
	public $label = 'id';
	public $column = 'id';

	/**
	 * Unique
	 */
	public $unique = true;

	public $primary = true;
	
	public $auto_increment = true;

	public $persist = false;

	public $add = false;
	
	public $edit = false;
	
	public $copy = false;


	/**
	 * Alter
	 */
	public function alter($table){
		$table -> id($this -> name);
	}


	/**
	 * Add the field to query to find the model
	 *
	 * @param Repository $repository
	 *
	 * @return Repository
	 */
	public function searchRepository($repository,$value,$table = null){

		if(!$table)
			$table = $this -> getObjectSchema() -> getTable();
		
		return $repository -> orWhere($this -> getColumn(),(int)$value);
	}

}

?>