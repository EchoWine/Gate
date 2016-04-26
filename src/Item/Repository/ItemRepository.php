<?php

namespace Item\Repository;

use CoreWine\DataBase\DB;

class ItemRepository{

	/**
	 * Schema
	 *
	 * @var Item\Schema\Item
	 */
	public $schema;

	/**
	 * Construct
	 *
	 * @param \Item\Schema\Item $schema
	 */
	public function __construct(\Item\Schema\Item $schema){
		$this -> schema = $schema;
	}

	/**
	 * Get the schema
	 *
	 * @return Item\Schema\Item
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Alter the schema of database
	 */
	public function __alterSchema(){

		$fields = $this -> getSchema() -> getFields();
		DB::schema($this -> getSchema() -> getTable(),function($table) use ($fields){
			foreach($fields as $name => $field){
				$field -> alter($table);
			}
		});
	}

	/**
	 * Get the QueryBuilder object
	 *
	 * @return CoreWine\DataBase\QueryBuilder
	 */
	public function table(){
		return DB::table($this -> getSchema() -> getTable());
	}

	/**
	 * Get all records
	 *
	 * @return Result
	 */
	public function all(){
		return $this -> table() -> get();
	}
}

?>