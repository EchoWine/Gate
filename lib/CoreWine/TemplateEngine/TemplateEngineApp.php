<?php

namespace CoreWine\TemplateEngine;

use CoreWine\Components\App;

class TemplateEngineApp extends App{

	public function __construct(){

		# Load template
		Engine::ini(PATH_APP."/cache/views");

	}

	public function app(){

	}

}

?>