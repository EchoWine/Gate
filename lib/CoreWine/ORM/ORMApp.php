<?php

namespace CoreWine\ORM;

use CoreWine\Components\App;

class ORMApp extends App{

	public function __construct(){
		SchemaBuilder::setFields(include PATH_CONFIG.'/orm.php');
	}

	public function app(){}

}

?>