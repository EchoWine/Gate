<?php

namespace CoreWine\ORM\Field\Integer;

use CoreWine\ORM\Field\Field\Schema as FieldSchema;

class Schema extends FieldSchema{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\Integer\Model';

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