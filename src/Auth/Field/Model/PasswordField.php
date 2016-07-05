<?php

namespace Auth\Field\Model;

use CoreWine\ORM\Field\Model\Field;
use Auth\Service\Auth;

class PasswordField extends Field{

	/**
	 * Parse the value from value to raw
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function parseValueToRaw($value){
		return Auth::getHashPass($value);
	}

}
?>