<?php

namespace Auth\Field\Password;

use CoreWine\ORM\Field\Model\Field;
use Auth\Service\Auth;

class Model extends Field{

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