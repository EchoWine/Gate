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
	 * Pagination
	 *
	 * @var Pagination
	 */
	public $pagination;

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

		$this -> setParserResult(function($rep,$results){
			$return = [];

			# Create EMPTY model if doens't exists and save in a stack
			# Otherwise retrieve

			foreach($results as $n => $result){
				if(!$rep -> isObjectORM($rep -> getModel(),$result[$rep -> getSchema() -> getPrimaryColumn()])){
					
					$model = $rep -> getModel()::new();
					$rep -> setObjectORM(
						$rep -> getModel(),
						$result[$rep -> getSchema() -> getPrimaryColumn()],
						$model
					);
				}else{
					$model = $rep -> getObjectORM($rep -> getModel(),$result[$rep -> getSchema() -> getPrimaryColumn()]);
					
				}

				$return[] = $model;
			}


			# Retrieve relations for this results
			$__relations = $rep -> retrieveRelations($results,$rep -> getSchema());

			# Getting all records for all relations
			# This call recursively setParserResult in order to create all ORM Object empty
			foreach($__relations as $relation => $columns){
				
				foreach($columns as $column => $values){
					$relation::repository() 
					-> whereIn($column,$values)
					-> get();
				}

			}

			# Fill all fields of ORM Object
			foreach($return as $n => $model){
				$model -> fillRawFromRepository($results[$n],$rep -> getObjectsORM());
				$model -> setPersist();
			}

			return $return;
		});

	}

	public function getPagination(){
		return $this -> pagination;
	}

	/**
	 * Get
	 */
	public function get(){
		$results = new CollectionResults(parent::get());
		$results -> setRepository($this);
		return $results;
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
			
			# N -> 1 || 1 -> 1
			if($field instanceof \CoreWine\ORM\Field\Schema\ModelField){
				foreach($results as $result){
					if(!empty($result[$field -> getColumn()])){
						if(!$this -> isObjectORM($field -> getRelation(),$result[$field -> getColumn()])){
							
							$relation[$field -> getRelation()][$field -> getRelation()::schema() -> getPrimaryColumn()][$result[$field -> getColumn()]] = $result[$field -> getColumn()];
						}
					}
				}
			}

			# 1 -> N
			if($field instanceof \CoreWine\ORM\Field\Schema\CollectionModelField){
				
				$field_relation = null;

				# Search the field that is relationated with this schema

				foreach($field -> getRelation()::schema() -> getFields() as $_field_relation){
					if($_field_relation instanceof \CoreWine\ORM\Field\Schema\ModelField){
						if($_field_relation -> getRelation() == $this -> getModel() && $field -> getReference() == $_field_relation -> getColumn()){
							
							$field_relation = $_field_relation;
						}
					}
				}
				if($field_relation !== null){
					foreach($results as $result){
						
						$relation[$field -> getRelation()][$field_relation -> getColumn()][$result[$schema -> getPrimaryColumn()]] = $result[$schema -> getPrimaryColumn()];
					}
				}
			}
		}

		return array_merge($relation,$relations);
	}

	/**
	 * Get all
	 *
	 * @return ORM\CollectionResults
	 */
	public function all(){
		return $this -> get();
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
	 * where primary
	 */
	public function wherePrimary($value){
		return $this -> where($this -> getSchema() -> getPrimaryColumn(),$value);
	}

	/**
	 * Get where primary
	 */
	public function firstByPrimary($value){
		return $this -> wherePrimary($value) -> first();
	}

	/**
	 * Paginate
	 *
	 * @param integer $show
	 * @param integer $page
	 *
	 * @return object pagination
	 */
	public function paginate($show,$page){

		$t = clone $this;
		
		$count = $this -> count();

		$pagination = new Pagination($count,$show,$page);

		$t -> take($pagination -> getShow());
		$t -> skip($pagination -> getSkip());
		$t -> pagination = $pagination;

		return $t;

	}
}

?>