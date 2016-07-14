<?php

namespace CoreWine\ORM\Field\ID;

use CoreWine\ORM\Field\Integer\Schema as IntegerSchema;

class Schema extends IntegerSchema{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\ID\Model';

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
}

?>