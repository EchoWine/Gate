<?php

class ItemModel extends Model{


	/**
	 * Primary key
	 */
	public $primary;

	/**
	 * List of all fields
	 */
	public $fields;

	/**
	 * Config
	 */
	public static $config;

	/**
	 * Add fields
	 * @param $a (array) list of fields to add
	 */
	public function setFields(array $a){
		foreach($a as $k)
			$this -> setField($k);
		
	}

	/**
	 * Add a field
	 * @param $k (object) field
	 */
	public function setField($k){
		$this -> fields[$k -> name] = $k;
		$k -> setModel($this);
	}

	/**
	 * Check all function
	 */
	public function check(){
		$this -> alterDatabase();
	}

	/**
	 * Make the table in DB
	 */
	public function alterDatabase(){

		foreach($this -> fields as $k){
			$k -> alterDatabase();
		}

	}

	/**
	 * Set primary key
	 * @param $p (string) name of field
	 */
	public function setPrimary($p){
		$this -> primary = $this -> fields[$p];
	}

	/**
	 * Select a record using the primary key
	 * @param $p (mixed) value of primary key
	 * @return (array) query result
	 */
	public function getByPrimary($p){
		return DB::table($this -> name) -> where($this -> primary,$p) -> get();
	}

	/**
	 * Select all record
	 * @param (int) $s start from
	 * @param (int) $n take n element
	 * @return (array) query result
	 */
	public function getResults($s = 0,$n = 5){
		return DB::table($this -> name) -> skip($s) -> take($n) -> lists();
	}

	/**
	 * Count all record
	 * @return (int) number of records
	 */
	public function countAll(){
		return DB::table($this -> name) -> count();
	}

	/**
	 * Get all fields name that will be print in list
	 * @return (array) fields name
	 */
	public function getFieldsNameInList(){
		$r = [];
		foreach($this -> fields as $k){
			if($k -> print -> list) $r[] = $k -> print -> list;
		}
		return $r;
	}

	/**
	 * Get all fields name that will be print in get
	 * @return (array) fields name
	 */
	public function getFieldsNameInGet(){
		$r = [];
		foreach($this -> fields as $k){
			if($k -> print -> get) $r[] = $k -> print -> get;
		}
		return $r;
	}

	public function add($f){

		$a = [];
		foreach($f as $k){
			$k -> add($a);
		}

		if(DB::table($this -> name) -> insert($a)){
			return new stdResponse(1,'Added','Success');
		}

		return new stdResponse(0,'Not Added','Error');


	}


}

?>