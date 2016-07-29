<?php

namespace CoreWine\ORM;

class RelationQueryBuilder{

	/**
	 * Relations
	 *
	 * @var array
	 */
	public $relations = [];

	public $count_relations = 0;

	/**
	 * Construct
	 *
	 * @param ORM\Model $model
	 * @param string $alias
	 */
	public function __construct($model,$alias){

		$this -> model = $model;
		$this -> alias = $alias;
		$this -> count_relations++;

	}

	public function resetCountRelation(){
		$this -> count_relations = 0;
	}

	public function addCountRelation(){
		$this -> count_relations++;
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
	 * Return name table
	 *
	 * @return string
	 */
	public function getNameTable(){
		$table = $this -> getSchema() -> getTable();

		if($this -> getAlias())
			return $table." as ".$this -> getAlias();

		return $table;
	}

	/**
	 * Return alias
	 *
	 * @return string
	 */
	public function getAlias(){
		return $this -> alias;
	}

	/**
	 * Get relation alias
	 *
	 * @return string
	 */
	public function getRelationBuilder($relations_way,$tab_from,$col_from,$tab_to,$col_to){

		$this -> addCountRelation();
		$count = $this -> count_relations;

		foreach($this -> getRelations() as $relation){
			if($relation -> is($relations_way,$tab_from,$col_from,$tab_to,$col_to)){
				$relation -> setNew(0);
				return $relation;
			}
		}

		$relation = new RelationBuilder($relations_way,$tab_from,$col_from,$tab_to,$col_to);

		# Search for existen table
		$relation -> setAliasFrom($this -> getAliasFrom($relations_way,$tab_from));

		# Create new alias
		$relation -> setAliasTo("_t".$count);
		$this -> relations[] = $relation;

		return $relation;
	}

	public function getAliasFrom($relations_way,$tab_from){

		$ways = explode(".",$relations_way);
		unset($ways[count($ways) - 1]);
		$relations_way = implode(".",$ways);

		foreach($this -> getRelations() as $relation){
			if($relation -> getFieldWay() == $relations_way)
				return $relation -> getAliasTo();
		}

		if($this -> getModel()::schema() -> getTable() == $tab_from)
			return $this -> getAlias();

		throw new \Exception("Error during creation alias in relations");
	}
	/**
	 * Get relations
	 *
	 * @var array
	 */
	public function getRelations(){
		return $this -> relations;
	}
}
?>