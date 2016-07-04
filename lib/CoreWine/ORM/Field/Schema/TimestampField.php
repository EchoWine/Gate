<?php

namespace CoreWine\ORM\Field\Schema;

class TimestampField extends IntegerField{
	
	/**
	 * Model
	 */
	public $__model = 'CoreWine\ORM\Field\Model\TimestampField';


	/**
	 * Alter
	 */
	public function alter($table){
		$table -> timestamp($this -> getColumn());
	}

}

?>