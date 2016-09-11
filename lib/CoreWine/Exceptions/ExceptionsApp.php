<?php

namespace CoreWine\Exceptions;

use CoreWine\Component\App;

class Application extends App{

	public function __construct(){
		new Handler('ExceptionsController','render');
	}

}

?>