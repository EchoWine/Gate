<?php

namespace Admin\Field\Schema;

use Auth\Service\Auth;

class PasswordField extends StringField{
	
	/**
	 * Regex of field
	 */
	public $minLength = 4;
	
	/**
	 * View edit
	 */
	public $viewGet = false;

	public function parseValue($value){
		return Auth::getHashPass($value);
	}
}

?>