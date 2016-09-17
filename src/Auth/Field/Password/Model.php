<?php

namespace Auth\Field\Password;

use CoreWine\DataBase\ORM\Field\Field\Model as FieldModel;

use Auth\Service\Auth;

class Model extends FieldModel{

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