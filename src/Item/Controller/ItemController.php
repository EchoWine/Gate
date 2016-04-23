<?php

namespace Item\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use CoreWine\SourceManager\Controller as Controller;

use Item\Repository\ItemRepository;

abstract class ItemController extends Controller{

	/**
	 * Item\Schema\Item
	 */
	public $schema;

	/**
	 * Check
	 */
	public function __check(){

		$this -> schema = new $this -> __schema();

		$repository = new ItemRepository($this -> schema);
		$repository -> alterSchema();
	}

	public static function all(){
		
	}
}


?>