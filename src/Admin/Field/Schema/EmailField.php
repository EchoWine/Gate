<?php

namespace Admin\Field\Schema;

class EmailField extends StringField{
	
	/**
	 * Regex of field
	 */
	public $regex = "/^.+\@.+\..+$/iU";


}

?>