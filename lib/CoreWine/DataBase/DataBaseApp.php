<?php

namespace CoreWine\DataBase;

use CoreWine\Components\App;

class DataBaseApp extends App{

	public function __construct(){
		DB::connect(include PATH_CONFIG.'/database.php');
	}



}

?>