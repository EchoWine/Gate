<?php

namespace Admin\Field\Schema;

class IdField extends NumberField{
	
	/**
	 * View List
	 */
	public $viewAll = true;

	public $add = false;
	
	public $edit = false;
	
	public $copy = false;



	/**
	 * Set DB schema
	 */
	public function alter($table){
		$table -> id($this -> name);
	}


}

?>