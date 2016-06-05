<?php

namespace Admin\Field\Schema;

class EmailField extends StringField{
	
	/**
	 * Unique
	 */
	public $unique = true;
	
	/**
	 * Regex of field
	 */
	public $regex = "/^.+\@.+\..+$/iU";

}

?>