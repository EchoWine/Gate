<?php

namespace Item;

use CoreWine\DataBase\DB;

class Repository{

	/**
	 * Schema
	 *
	 * @var Item\Schema
	 */
	public $schema;

	/**
	 * Construct
	 *
	 * @param \Item\Schema $schema
	 */
	public function __construct(\Item\Schema $schema){
		$this -> schema = $schema;
	}

	/**
	 * Get the schema
	 *
	 * @return Item\Schema
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
		return DB::table($this -> getSchema() -> getTable()) -> setParserResult(function($results){
			
			foreach($results as $n => $result){
				$results[$n] = $this -> schema -> parseResult($result);
			}

			return $results;
		});
	}

	/**
	 * Get all records
	 *
	 * @return Result
	 */
	public function get(){
		return $this -> table() -> get();
	}


}

?>