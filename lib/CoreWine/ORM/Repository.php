<?php

namespace CoreWine\ORM;

use CoreWine\DataBase\DB;
use CoreWine\DataBase\QueryBuilder;

class Repository extends QueryBuilder{

	/**
	 * Model
	 *
	 * @var ORM\Model
	 */
	public $model;

	/**
	 * Construct
	 *
	 * @param \ORM\Schema $model
	 */
	public function __construct($model){
		$this -> model = $model;


		parent::__construct($this -> getSchema() -> getTable());

		$this -> setParserResult(function($results){

			$return = [];

			foreach($results as $n => $result){
				$model = $this -> getModel()::new();
				$model -> fillRawFromRepository($result);
				$model -> setPersist();
				$return[] = $model;
			}

			return $return;
		});

	}

	/**
	 * Get the model
	 *
	 * @return ORM\Schema
	 */
	public function getModel(){
		return $this -> model;
	}


	/**
	 * Get the schema
	 *
	 * @return ORM\Schema
	 */
	public function getSchema(){
		return $this -> getModel()::schema();
	}

	/**
	 * Alter the schema of database
	 */
	public function alterSchema(){

		$fields = $this -> getSchema() -> getFields();
		DB::schema($this -> getSchema() -> getTable(),function($table) use ($fields){
			foreach($fields as $name => $field){
				$field -> alter($table);
			}
		});
	}

	/**
	 * Get where primary
	 */
	public function firstByPrimary($value){
		return $this -> where('id',$value) -> first();
	}


}

?>