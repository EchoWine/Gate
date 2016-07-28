<?php

namespace CoreWine\ORM;


class RelationBuilder{

	public $table_from;
	public $col_from;
	public $table_to;
	public $col_to;
	public $_new = 0;
	public $alias_from;
	public $alias_to;

	public function __construct($table_from,$col_from,$table_to,$col_to){
		$this -> table_from = $table_from;
		$this -> col_from = $col_from;
		$this -> table_to = $table_to;
		$this -> col_to = $col_to;
	}

	public function is($table_from,$col_from,$table_to,$col_to){
		return $this -> table_from == $table_from && $this -> col_from == $col_from &&
		$this -> table_to == $table_to && $this -> col_to == $col_to;

	}

	public function setNew($new){
		$this -> _new = $new;
	}

	public function getNew(){
		return $this -> _new;
	}

	public function setAliasFrom($alias_from){
		$this -> alias_from = $alias_from;
	}

	public function setAliasTo($alias_to){
		$this -> alias_to = $alias_to;
	}

	public function getAliasFrom(){
		return $this -> alias_from;
	}

	public function getAliasTo(){
		return $this -> alias_to;
	}

	public function getTableTo(){
		return $this -> table_to;
	}

}