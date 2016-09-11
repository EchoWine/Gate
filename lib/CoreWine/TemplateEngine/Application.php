<?php

namespace CoreWine\TemplateEngine;

use CoreWine\Component\App;

class Application extends App{

	public function __construct(){

		# Load template
		Engine::ini(PATH_APP."/cache/views");

	}

	public function app(){

	}

}

?>