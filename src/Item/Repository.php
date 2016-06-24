<?php

namespace Item;

use CoreWine\DataBase\DB;

class Repository extends \CoreWine\DataBase\QueryBuilder{

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

		parent::__construct($schema -> getTable());

		$this -> setParserResult(function($results){
			foreach($results as $n => $result){
				$results[$n] = $this -> getSchema() -> parseResult($result);
			}

			return $results;
		});
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


}

?>