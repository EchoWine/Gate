<?php

namespace Api\Service;

use CoreWine\Http\Request;

class Api{

	/**
	 * Get basic path api 
	 *
	 * @return string
	 */
	public static function url(){

		return Request::getDirUrl()."api/v1/";
	}
}


?>