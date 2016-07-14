<?php

namespace CoreWine\ORM\Field\Integer;

use CoreWine\ORM\Field\Field\Schema as FieldSchema;

class Schema extends FieldSchema{

	/**
	 * Lenght
	 */
	public $maxLength = 11;

	/**
	 * Lenght
	 */
	public $minLength = 0;

	/**
	 * Alter
	 */
	public function alter($table){
		$table -> int($this -> getColumn(),$this -> getMaxLength());
	}

}

?>