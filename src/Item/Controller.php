<?php

namespace Item;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use CoreWine\SourceManager\Controller as Controller;

use Item\Repository;

abstract class Controller extends Controller{


	/**
	 * Item\Schema
	 */
	public $__schema = 'Item\Schema';

	/**
	 * Item\Repository
	 */
	public $__repository = 'Item\Repository';

	/**
	 * Item\Schema
	 */
	public $schema;

	/**
	 * Item\Repository
	 */
	public $repository;

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

	public function __all(){
		$results = $this -> getRepository() -> get();

		$return = [];
		foreach($results as $n => $result){
			$results[$n] = (object)$result;
		}

		return $results;
	}
}


?>