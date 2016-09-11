<?php

namespace CoreWine\DataBase;

use CoreWine\Components\App;

class Application extends App{

	public function __construct(){
		DB::connect(include PATH_CONFIG.'/database.php');
	}



}

?>