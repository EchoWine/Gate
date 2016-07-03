<?php

namespace CoreWine\ORM;

use CoreWine\DataBase\DB;
use CoreWine\DataBase\QueryBuilder;

class Repository extends QueryBuilder{

	/**
	 * Model
	 *
	 * @var string ORM\Model
	 */
	public $model;

	/**
	 * List of all objects ORM
	 *
	 * @var Array ORM\Model
	 */
	public static $objects_ORM = [];

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

			# Create EMPTY model if doens't exists and save in a stack
			# Otherwise retrieve

			foreach($results as $n => $result){
				if(!$this -> isObjectORM($this -> getModel(),$result[$this -> getSchema() -> getPrimaryField() -> getColumn()])){
					
					$model = $this -> getModel()::new();
					$this -> setObjectORM(
						$this -> getModel(),
						$result[$this -> getSchema() -> getPrimaryField() -> getColumn()],
						$model
					);
				}else{
					$model = $this -> getObjectORM($this -> getModel(),$result[$this -> getSchema() -> getPrimaryField() -> getColumn()]);
					
				}

				$return[] = $model;
			}

			# Retrieve relations for this results
			$__relations = $this -> retrieveRelations($results,$this -> getSchema());


			# Getting all records for all relations
			# This call recursively setParserResult in order to create all ORM Object empty
			foreach($__relations as $relation => $values){
				
				$relation::repository() 
				-> whereIn($relation::schema() -> getPrimaryField() -> getColumn(),$values)
				-> get();

			}


			# Fill all fields of ORM Object
			foreach($return as $n => $model){
				$model -> fillRawFromRepository($results[$n],$this -> getObjectsORM());
				$model -> setPersist();
			}

			

			return $return;
		});

	}

	/**
	 * Set object ORM
	 *
	 * @param string $name
	 * @param mixed $primary
	 * @param ORM\Model $obj
	 */
	public function setObjectORM($name,$primary,$obj){
		static::$objects_ORM[$name][$primary] = $obj;
	}

	/**
	 * Get object ORM
	 *
	 * @param string $name
	 * @param mixed $primary
	 *
	 * @return ORM\Model $obj
	 */
	public function getObjectORM($name,$primary){
		return static::$objects_ORM[$name][$primary];
	}

	/**
	 * Exists object ORM
	 *
	 * @param string $name
	 * @param mixed $primary
	 *
	 * @return bool
	 */
	public function isObjectORM($name,$primary){
		return isset(static::$objects_ORM[$name][$primary]);
	}

	/**
	 * Get all objects ORM
	 *
	 * @return Array ORM\Model
	 */
	public function getObjectsORM(){
		return static::$objects_ORM;
	}

	/**
	 * Get relations for this schema and results
	 *
	 * @param array $results
	 * @param Schema $schema
	 * @param array $relations
	 *
	 * @return array
	 */
	public function retrieveRelations($results,$schema,$relations = []){
		$relation = [];
		foreach($schema -> getFields() as $field){
			

			if($field instanceof \CoreWine\ORM\Field\Schema\ModelField){
				foreach($results as $result){
					if(!empty($result[$field -> getColumn()])){
						if(!$this -> isObjectORM($field -> getRelation(),$result[$field -> getColumn()])){
							
							//print_r($this -> getObjectsORM());
							$relation[$field -> getRelation()][] = $result[$field -> getColumn()];
							//print_r($relation);
						}
					}
				}
			}
		}

		return array_merge($relation,$relations);
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