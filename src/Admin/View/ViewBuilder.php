<?php

namespace Admin\View;

use Exception;

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
	public $select = null;

	/**
	 * Display a input
	 *
	 * @var Component\Select
	 */
	public $input = true;

	/**
	 * Label
	 *
	 * @var string
	 */
	public $label;

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
		$this -> label($this -> getName());	

		if($this -> getSchema() -> getType() == "to_one" || $this -> getSchema() -> getType() == "to_many"){
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

		if($last_relation -> getType() == "to_one" || $last_relation -> getType() == "to_many"){
				
			if($last_relation -> getRelation()::schema() -> isField($method)){

				$field = $last_relation -> getRelation()::schema() -> getField($method);
				//$this -> schema = $field;
				$this -> relations[] = $field;

				$this -> label($this -> getName());	

				if($field -> getType() == "to_one" || $field -> getType() == "to_many"){
					if(isset($arguments[0]))
						$this -> urls[] = $arguments[0];
				}

				return $this;
			}
		}

		$this -> label($this -> getName());	
		
		throw new Exceptions\UndefinedMethodException(static::class,$method);
	}

	/**
	 * Display a select
	 */
	public function select($url,$value,$label = null,$search = null){
		$this -> select = new Component\Select($url,$value,$label,$search);
		return $this;
	}

	public function isSelect(){
		return $this -> select !== null;
	}

	public function isInput(){
		return $this -> input;
	}

	public function getSelect(){
		return $this -> select;
	}

	/**
	 * Display a input
	 */
	public function input(){
		$this -> input = true;
		return $this;
	}

	/**
	 * Set label
	 */
	public function label($label){
		$this -> label = $label;
		return $this;
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

	/**
	 * Get relations
	 *
	 * @return array
	 */
	public function getLastColumnRelation(){
		return $this -> relations[count($this -> relations) - 2];
	}

	public function countRelations(){
		return count($this -> relations);
	}

	public function getLabel(){
		return $this -> label;
	}

	public function getName(){
		return implode(".",array_map(function($item){ return $item -> getName(); },$this -> getRelations()));
	}

	public function getColumn(){
		return $this -> getSchema() -> getColumn();
	}
}
?>