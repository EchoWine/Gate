<?php

namespace CoreWine\Item;

use CoreWine\DataBase\DB;
use CoreWine\DataBase\QueryBuilder;

class Repository extends QueryBuilder{

	/**
	 * Entity
	 *
	 * @var Item\Entity
	 */
	public $entity;

	/**
	 * Construct
	 *
	 * @param \Item\Schema $entity
	 */
	public function __construct($entity){
		$this -> entity = $entity;


		parent::__construct($this -> getSchema() -> getTable());

		$this -> setParserResult(function($results){

			$return = [];

			foreach($results as $n => $result){
				$entity = $this -> getEntity()::new();
				$entity -> fillRaw($result);
				$entity -> setPersist();
				$return[] = $entity;
			}

			return $return;
		});

	}

	/**
	 * Get the entity
	 *
	 * @return Item\Schema
	 */
	public function getEntity(){
		return $this -> entity;
	}


	/**
	 * Get the schema
	 *
	 * @return Item\Schema
	 */
	public function getSchema(){
		$entity = $this -> entity;
		return $entity::schema();
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