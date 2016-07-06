<?php

namespace CoreWine\Exceptions;

use CoreWine\Components\App;

class ExceptionsApp extends App{

	public function __construct(){
		new Handler('ExceptionsController','render');
	}

}

?>