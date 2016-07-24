<?php

namespace Admin\View;

use CoreWine\Exceptions as Exceptions;

class ViewBuilder{

	/**
	 * Schema
	 *
	 * @var ORM\Schema
	 */
	public $schema;

	/**
	 * Display a select
	 *
	 * @var bool
	 */
	public $select = false;

	/**
	 * Display a input
	 *
	 * @var bool
	 */
	public $input = false;

	/**
	 * Relations
	 *
	 * @var array
	 */
	public $relations = [];

	/**
	 * urls
	 *
	 * @var array
	 */
	public $urls = [];

	/**
	 * Construct
	 */
	public function __construct($schema,$arguments){
		$this -> schema = $schema;

		$this -> relations[] = $schema;

		if($this -> getSchema() -> getType() == "model"){
			if(isset($arguments[0]))
				$this -> urls[] = $arguments[0];
		}
	}

	/**
	 * Get schema
	 *
	 * @return ORM\Schema
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	public function __call($method,$arguments){

		$last_relation = $this -> getLastRelation();

		if($last_relation -> getType() == "model"){
			if($last_relation -> getRelation()::schema() -> isField($method)){

				$field = $last_relation -> getRelation()::schema() -> getField($method);
				//$this -> schema = $field;
				$this -> relations[] = $field;

				if($field -> getType() == "model"){
					if(isset($arguments[0]))
						$this -> urls[] = $arguments[0];
				}

				return $this;
			}
		}


		throw new Exceptions\UndefinedMethodException(static::class,$method);
	}

	/**
	 * Display a select
	 */
	public function select(){
		$this -> select = true;
	}

	/**
	 * Display a input
	 */
	public function input(){
		$this -> input = true;
	}

	/**
	 * Get relations
	 *
	 * @return array
	 */
	public function getRelations(){
		return $this -> relations;
	}

	public function getUrl($n){
		return isset($this -> urls[$n]) ? $this -> urls[$n] : null;
	}

	/**
	 * Get relations
	 *
	 * @return array
	 */
	public function getLastRelation(){
		return $this -> relations[count($this -> relations) - 1];
	}

	public function getLabel(){
		return $this -> getSchema() -> getLabel();
	}

	public function getName(){
		return implode(".",array_map(function($item){ return $item -> getName(); },$this -> getRelations()));
	}

}
?>