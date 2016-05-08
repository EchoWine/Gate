<?php

namespace Admin\Field\Schema;

class UsernameField extends StringField{
	
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