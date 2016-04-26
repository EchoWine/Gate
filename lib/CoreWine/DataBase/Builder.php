<?php

namespace CoreWine\DataBase;

/**
 * Builder
 */
class Builder{


	/**
	 * Counter of all istance of this class
	 */
	public static $counter = 0;

	/**
	 * Count of actual istance
	 */
	public $count;

	/**
	 * Table
	 */
	public $table;

	/**
	 * Aggregate (count/min/max) not used
	 */
	public $agg = [];

	/**
	 * Select
	 */
	public $select = [];

	/**
	 * Update
	 */
	public $update = [];

	/**
	 * Order by
	 */
	public $orderby = [];

	/**
	 * Skip N records
	 */
	public $skip = NULL;
	
	/**
	 * Take N records
	 */	
	public $take = NULL;
	
	/**
	 * Group by
	 */	
	public $groupBy = [];
	
	/**
	 * and Where
	 */		
	public $andWhere = [];
	
	/**
	 * or Where
	 */		
	public $orWhere = [];
	
	/**
	 * join
	 */		
	public $join = [];
	
	/**
	 * and on
	 */		
	public $andOn = [];
	
	/**
	 * or on
	 */	
	public $orOn = [];

	
	/**
	 * and having
	 */		
	public $andHaving = [];
	
	/**
	 * or having
	 */	
	public $orHaving = [];
	
	/**
	 * union
	 */
	public $union = [];
	
	/**
	 * Column that will be used as index in array result, e.g. ID
	 */
	public $indexResult = "";
			
	/**
	 * Prepare
	 */
	public $prepare = [];
	
	/**
	 * last joined table
	 */
	public $lastJoinTable = null;

	/**
	 * List of all the alias made automatically for the nested selection query
	 */
	public static $tableAs = array();

	/**
	 * Callback Parse data
	 */
	public $parserResult = null;

	public function __construct(){}

	/**
	 * Set prepare
	 * 
	 * @param array $prepare
	 */
	public function setPrepare($prepare){
		$this -> prepare = $prepare;
	}

	/**
	 * @return array prepare
	 */
	public function getPrepare(){
		return $this -> prepare;
	}

	/**
	 * Add a var
	 *
	 * @param string $index
	 * @param string $prepare
	 */
	public function addPrepare($index,$prepare){
		$this -> prepare[$index] = $prepare;
	}

	/**
	 * Clone 
	 */
	public function __clone(){
		$this -> incCount();
	}

	/**
	 * Increment the counter
	 */
	public function incCount(){
		$this -> count = self::$counter++;
	}

	/**
	 * Get counter
	 */
	public function getCount(){
		return $this -> count;
	}


	/**
	 * Return a random name (unused) to use as alias for the query
	 *
	 * @return string alias name of the table
	 */
	public static function getTableAs(){
		$c = "t".count(self::$tableAs);
		self::$tableAs[] = $c;
		return $c;
	}

	/**
	 * Add a table
	 *
	 * @param string $table
	 * @return string $alias
	 */
	public function addTable($table,$alias = ''){

		if($table instanceof QueryBuilder){
			if($alias === null){
				$alias = self::getTableAs();
			}
		}

		$this -> table = $table;
		$this -> alias = $alias;

		$this -> setLastJoinTable($table);
	}


	/**
	 * @return string last joined table
	 */
	public function getLastJoinTable(){
		return $this -> lastJoinTable;
	}
	
	/**
	 * @param string $table last joined table
	 */
	public function setLastJoinTable($table){
		$this -> lastJoinTable = $table;
	}

	/**
	 * @param string $sql add a select
	 */
	public function addSelect($sql){
		$this -> select[] = $sql;
	}

}