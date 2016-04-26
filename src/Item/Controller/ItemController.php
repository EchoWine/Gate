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
	 * Item\Repository\ItemRepository
	 */
	public $__repository = 'Item\Repository\ItemRepository';

	/**
	 * Check
	 */
	public function __check(){
		$this -> schema = new $this -> __schema();
		$this -> repository = new $this -> __repository($this -> schema);
		$this -> __alterSchema();
	}

	public function __alterSchema(){
		$this -> getRepository() -> __alterSchema();
	}

	public function getSchema(){
		return $this -> schema;
	}

	public function getRepository(){
		return $this -> repository;
	}

	public function all(){
		
	}
}


?>