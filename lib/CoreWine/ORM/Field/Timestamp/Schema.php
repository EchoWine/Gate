<?php

namespace CoreWine\ORM\Field\Timestamp;

use CoreWine\ORM\Field\Integer\Schema as IntegerSchema;

class Schema extends IntegerSchema{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\Timestamp\Model';

	/**
	 * Alter
	 */
	public function alter($table){
		$table -> timestamp($this -> getColumn());
	}

}

?>