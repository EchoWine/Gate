<?php

namespace CoreWine\DataBase;

/**
 * Schema Table
 */
class SchemaTable{

	/**
	 * Name of the table
	 */
	public $name;

	/**
	 * List of columns
	 */
	public $columns = [];

	/**
	 * Construct
	 *
	 * @param string $name name of table
	 * @param array $columns list of columns
	 */
	public function __construct($name,$columns = []){

		$this -> name = strtolower($name);
		$this -> columns = $columns;
	}	

	/**
	 * @return string name of table
	 */
	public function getName(){
		return $this -> name;
	}

	/**
	 * Add a column
	 *
	 * @param SchemaColumn $column
	 */
	public function addColumn(SchemaColumn $column){
		$this -> columns[$column -> getName()] = $column;
	}

	/**
	 * Set a column
	 *
	 * @param SchemaColumn $column
	 */
	public function setColumn($column){
		$this -> columns[$column -> getName()] = $column;
	}

	/**
	 * Remove a column by name
	 *
	 * @param string $columnName
	 */
	public function dropColumn($columnName){
		unset($this -> columns[$columnName]);
	}

	/**
	 * @return array all columns
	 */
	public function getColumns(){
		return $this -> columns;
	}

	/**
	 * Return a column by it's name if exists
	 *
	 * @param string $columnName
	 * @return SchemaColumn column
	 */
	public function getColumn(string $columnName){
		return $this -> hasColumn($columnName) ? $this -> columns[$columnName] : null;
	}

	/**
	 * Return if has column 
	 *
	 * @param string $columnName
	 * @return bool
	 */
	public function hasColumn(string $columnName){
		return isset($this -> columns[$columnName]);
	}
	
	/**
	 * @return int count of columns
	 */
	public function countColumns(){
		return count($this -> columns);
	}

	/**
	 * @return bool has the table a primary key?
	 */
	public function hasPrimary(){
		foreach($this -> columns as $k){
			if($k -> getPrimary())
				return true;
		}

		return false;
	}

	/**
	 * @return SchemaColumn column that is primary key
	 */
	public function getPrimary(){
		foreach($this -> columns as $k){
			if($k -> getPrimary())
				return $k;
		}

		return null;
	}

	/**
	 * @return bool has the table a column with auto increment?
	 */
	public function hasAutoIncrement(){
		foreach($this -> columns as $k){
			if($k -> getAutoIncrement())
				return true;
		}

		return false;
	}

	/**
	 * Drop the primary key
	 */
	public function dropPrimary(){
		foreach($this -> columns as $k){
			if($k -> getPrimary())
				$k -> setPrimary(false);
		}
	}

	/**
	 * Get the column that has a foreign key related to the table
	 * 
	 * @param string $tableName
	 * @return SchemaColumn column
	 */
	public function getForeignKeyTo($tableName){

		foreach($this -> columns as $k){
			if($k -> getForeignTable() == $tableName)
				return $k;

		}
		return null;
	}

	/**
	 * Get the column that has a foreign key related to the table and a specific column
	 * 
	 * @param string $tableName
	 * @param string $columnName
	 * @return SchemaColumn column
	 */
	public function getForeignKeyToColumn($tableName,$columnName){

		foreach($this -> columns as $k){
			if($k -> getForeignTable() == $tableName && $k -> getForeignColumn() == $columnName)
				return $k;

		}
		return null;
	}
}