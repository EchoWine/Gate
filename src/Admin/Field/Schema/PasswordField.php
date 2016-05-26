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

	/**
	 * Edit if empty
	 *
	 * If this value is set to false and the value of field sent in update operation is empty,
	 * then this field will be removed in edit/update operation
	 */
	public $editIfEmpty = false;

	public function parseValue($value){
		return Auth::getHashPass($value);
	}
}

?>