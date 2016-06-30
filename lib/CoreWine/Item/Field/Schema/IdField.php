<?php

namespace CoreWine\Item\Field\Schema;

class IdField extends Field{
	
	/**
	 * Unique
	 */
	public $unique = true;

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