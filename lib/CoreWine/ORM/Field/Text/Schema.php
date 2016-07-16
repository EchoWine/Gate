<?php

namespace CoreWine\ORM\Field\Text;

use CoreWine\ORM\Field\Field\Schema as FieldSchema;

class Schema extends FieldSchema{
	
	/**
	 * Max length
	 *
	 * @var integer
	 */
	public $max_length = 65535;

	/**
	 * Alter
	 */
	public function alter($table){
		$col = $table -> text($this -> getColumn(),$this -> getMaxLength());

		if(!$this -> getRequired())
			$col -> null();
	}
}

?>