<?php

namespace CoreWine\ORM\Field\Schema;

class IntegerField extends Field{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\Model\IntegerField';

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