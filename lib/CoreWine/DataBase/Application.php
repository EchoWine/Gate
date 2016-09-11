<?php

namespace CoreWine\DataBase;

use CoreWine\Component\App;

class Application extends App{

	public function __construct(){
		DB::connect(include PATH_CONFIG.'/database.php');
	}



}

?>