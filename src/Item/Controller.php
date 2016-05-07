<?php

namespace Item;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use CoreWine\SourceManager\Controller as Controller;

use Item\Repository;

abstract class Controller extends Controller{

	/**
	 * Retrieve result as array
	 */
	const RESULT_ARRAY = 0;

	/**
	 * Retrieve results as object
	 */
	const RESULT_OBJECT = 1;

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

	/**
	 * Alter schema
	 */
	public function __alterSchema(){
		$this -> getRepository() -> __alterSchema();
	}

	/**
	 * Get schema
	 *
	 * @return Schema
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Get repository
	 *
	 * @return Repository
	 */
	public function getRepository(){
		return $this -> repository;
	}

	/**
	 * Get all records
	 *
	 * @param int $type type of result (Array|Object)
	 * @return results
	 */
	public function __all($type){
		return $this -> getRepository() -> get($type);
	}

	/**
	 * Add a new record
	 */
	public function __add(){

		$row = [];

		$fields = $this -> getSchema() -> getFields();
		foreach($fields as $name => $field){
			$row[$field -> getName()] = Request::put($field -> getName());
		}


		// Validate fields
		if(false){
			return false;
		}


		// Check unique fields
		if(false){
			return false;
		}



		// Insert
		return $this -> getRepository() -> insert($row);

	}
}


?>