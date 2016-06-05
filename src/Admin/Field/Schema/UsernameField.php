<?php

namespace Admin\Field\Schema;

class UsernameField extends StringField{
	
	/**
	 * Unique
	 */
	public $unique = true;
	
	/**
	 * Regex of field
	 */
	public $regex = "/^([\w]*)$/iU";

	/**
	 * Min length
	 */
	public $minLength = 3;


}

?>