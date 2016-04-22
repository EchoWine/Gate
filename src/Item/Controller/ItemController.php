<?php

namespace Item\Controller;

use CoreWine\DB as DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use Item\Repository\ItemRepository;

abstract class ItemController{

	public $item;

	public function __routes(){}
	public function __check(){
		$repository = new ItemRepository($this -> item);
		$repository -> alterSchema();
	}

	public function all(){
		
	}
}

?>