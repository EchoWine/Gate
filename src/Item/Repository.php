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
	public function table($type = 1){

		switch($type){
			case null:
				return DB::table($this -> getSchema() -> getTable());
			break;
			case 0:

				return DB::table($this -> getSchema() -> getTable()) -> setParserResult(function($results){
					
					return $results;
				});
			break;

			case 1:

				return DB::table($this -> getSchema() -> getTable()) -> setParserResult(function($results){
					
					foreach($results as $n => $result){
						$results[$n] = $this -> schema -> parseResult($result);
					}

					return $results;
				});
			break;

		}
	}

	/**
	 * Get all records
	 *
	 * @return Result
	 */
	public function get($type){
		return $this -> table($type) -> orderByDesc('id') -> get();
	}

	/**
	 * Insert a new record
	 *
	 * @param array $values
	 * @return int id of new record
	 */
	public function insert($values){
		return $this -> table(null) -> insert($values);
	}


}

?>