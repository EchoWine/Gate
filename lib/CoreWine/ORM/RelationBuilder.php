<?php

namespace CoreWine\ORM;


class RelationBuilder{

	/**
	 * Table from
	 *
	 * @var string
	 */
	public $table_from;

	/**
	 * Column from
	 *
	 * @var string
	 */
	public $col_from;

	/**
	 * Table to
	 *
	 * @var string
	 */
	public $table_to;

	/**
	 * Column to
	 *
	 * @var string
	 */
	public $col_to;

	/**
	 * Alias table from
	 *
	 * @var string
	 */
	public $alias_from;

	/**
	 * Alias table to
	 *
	 * @var string
	 */
	public $alias_to;

	/**
	 * String that indicates all relations
	 *
	 * @var string
	 */
	public $relations_way;

	/**
	 * Is this a new relation
	 *
	 * @var bool
	 */
	public $_new = 1;

	/**
	 * Construct
	 *
	 * @param string $relations_way
	 * @param string $table_from
	 * @param string $col_from
	 * @param string $table_to
	 * @param string $col_to
	 */
	public function __construct($relations_way,$table_from,$col_from,$table_to,$col_to){
		$this -> relations_way = $relations_way;
		$this -> table_from = $table_from;
		$this -> col_from = $col_from;
		$this -> table_to = $table_to;
		$this -> col_to = $col_to;
	}

	/**
	 * Is equal
	 *
	 * @param string $relations_way
	 * @param string $table_from
	 * @param string $col_from
	 * @param string $table_to
	 * @param string $col_to
	 *
	 * @return bool
	 */
	public function is($relations_way,$table_from,$col_from,$table_to,$col_to){
		return $this -> table_from == $table_from && $this -> col_from == $col_from &&
		$this -> table_to == $table_to && $this -> col_to == $col_to &&
		$this -> relations_way == $relations_way;

	}

	/**
	 * Get field way
	 *
	 * @return string
	 */
	public function getFieldWay(){
		return $this -> relations_way;
	}

	/**
	 * Set new
	 *
	 * @param bool $new
	 */
	public function setNew($new){
		$this -> _new = $new;
	}

	/**
	 * Get new
	 *
	 * @return bool
	 */
	public function getNew(){
		return $this -> _new;
	}

	/**
	 * Set alias from
	 *
	 * @param string $alias_from
	 */
	public function setAliasFrom($alias_from){
		$this -> alias_from = $alias_from;
	}

	/**
	 * Set alias to
	 *
	 * @param string $alias_to
	 */
	public function setAliasTo($alias_to){
		$this -> alias_to = $alias_to;
	}

	/**
	 * Get alias from
	 *
	 * @return string
	 */
	public function getAliasFrom(){
		return $this -> alias_from;
	}

	/**
	 * Get alias to
	 *
	 * @return string
	 */
	public function getAliasTo(){
		return $this -> alias_to;
	}

	/**
	 * Get table to
	 *
	 * @return string
	 */
	public function getTableTo(){
		return $this -> table_to;
	}

}