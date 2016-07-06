<?php

namespace CoreWine\Requirements;

use CoreWine\Components\App;

class RequirementsApp extends App{

	public function __construct(){
		Dependency::setPHPVersion('7.0.0');
		Dependency::check();
	}

	public function app(){}

}

?>