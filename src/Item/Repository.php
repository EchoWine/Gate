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

		$table = DB::table($this -> getSchema() -> getTable());


		switch($type){
			case null:
				return $table;
			break;
			case 0:

				return $table -> setParserResult(function($results){
					
					return $results;
				});
			break;

			case 1:

				return $table -> setParserResult(function($results){
					
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
	 * Get first record by id
	 *
	 * @return Result
	 */
	public function firstById($id,$type = null){
		return $this -> table($type) -> where('id',$id) -> first();
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

	/**
	 * Update a record
	 *
	 * @param array $values
	 * @return int id of new record
	 */
	public function update($id,$values){
		return $this -> table(null) -> where('id',$id) -> update($values);
	}

	/**
	 * Delete by id
	 *
	 * @return Result
	 */
	public function deleteById($id){
		return $this -> table(null) -> where('id',$id) -> delete();
	}


}

?>