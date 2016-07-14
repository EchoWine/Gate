<?php

namespace CoreWine\ORM\Field\Timestamp;

use CoreWine\ORM\Field\Integer\Schema as IntegerSchema;

class Schema extends IntegerSchema{
	
	/**
	 * Alter
	 */
	public function alter($table){
		$table -> timestamp($this -> getColumn());
	}

}

?>