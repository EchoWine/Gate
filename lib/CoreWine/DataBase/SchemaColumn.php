<?php

namespace CoreWine\DataBase;

/**
 * Schema Column
 */
class SchemaColumn{

	/**
	 * Name of table
	 */
	public $table;

	/**
	 * Name of column
	 */
	public $name;

	/**
	 * Type of column
	 */
	public $type;

	/**
	 * Length of column
	 */
	public $length = null;

	/**
	 * Is null
	 */
	public $null = true;

	/**
	 * Default value
	 */
	public $default = null;

	/**
	 * Is a primary key
	 */
	public $primary = false;

	/**
	 * Is unique
	 */
	public $unique = false;

	/**
	 * Is autoincrement
	 */
	public $auto_increment = false;
	
	/**
	 * Is a index
	 */
	public $index = null;
	
	/**
	 * Name of constraint (foreign key)
	 */	
	public $constraint = '';

	/**
	 * Foreign key column
	 */	
	public $foreignColumn;

	/**
	 * Foreign key table
	 */	
	public $foreignTable;

	/**
	 * Foreign on delete
	 */
	public $foreignDelete;

	/**
	 * Foreign on update
	 */
	public $foreignUpdate;

	/**
	 * Construct
	 */
	public function __construct($params){

		foreach($params as $n => $param){
			$this -> {$n} = $param;
		}

	}

	/**
	 * @return string table
	 */
	public function getTable(){
		return $this -> table;
	}

	/**
	 * @return string name column
	 */
	public function getName(){
		return $this -> name;
	}

	/**
	 * @return string index
	 */
	public function getIndex(){
		return $this -> index;
	}

	/**
	 * @return bool has index?
	 */
	public function hasIndex(){
		return !empty($this -> index);
	}

	/**
	 * @return bool auto increment
	 */
	public function getAutoIncrement(){
		return $this -> auto_increment;
	}

	/**
	 * @return bool primary
	 */
	public function getPrimary(){
		return $this -> primary;
	}

	/**
	 * @return bool unique
	 */
	public function getUnique(){
		return $this -> unique;
	}

	/**
	 * @return bool is null
	 */
	public function getNull(){
		return $this -> null;
	}

	/**
	 * @return string default
	 */
	public function getDefault(){
		return $this -> default;
	}

	/**
	 * @return int lenght
	 */
	public function getLength(){
		return $this -> length;
	}

	/**
	 * @return string type
	 */
	public function getType(){
		return $this -> type;
	}

	/**
	 * @param string $index
	 */
	public function setIndex(String $index = null){
		$this -> index = $index;
	}

	/**
	 * @param string $table
	 * @param string $column
	 */
	public function setForeign(string $table = null,string $column = null){
		$this -> foreignTable = $table;
		$this -> foreignColumn = $column;
	}

	/**
	 * @param string $delete sql code
	 */
	public function setForeignDelete($delete){
		$this -> foreignDelete = $delete;
	}

	/**
	 * @param string $update sql code
	 */
	public function setForeignUpdate($update){
		$this -> foreignUpdate = $update;
	}

	/**
	 * @param string $constraint
	 */
	public function setConstraint($constraint){
		$this -> constraint = $constraint;
	}

	/**
	 * @return bool has a foreign key?
	 */
	public function getForeign(){
		return !empty($this -> foreignTable) && !empty($this -> foreignColumn);
	}

	/**
	 * @return string constraint
	 */
	public function getConstraint(){
		return $this -> constraint;
	}

	/**
	 * @return string foreign key table
	 */
	public function getForeignTable(){
		return $this -> foreignTable;
	}

	/**
	 * @return string foreign key table
	 */
	public function getForeignColumn(){
		return $this -> foreignColumn;
	}
	
	/**
	 * @return string foreign on delete
	 */
	public function getForeignDelete(){
		return $this -> foreignDelete;
	}

	/**
	 * @return string foreign on update
	 */
	public function getForeignUpdate(){
		return $this -> foreignUpdate;
	}

	/**
	 * @param bool $auto_increment
	 */
	public function setAutoIncrement(bool $auto_increment){
		$this -> auto_increment = $auto_increment;
	}

	/**
	 * @param bool $primary
	 */
	public function setPrimary(bool $primary){
		$this -> primary = $primary;
	}

	/**
	 * @param bool $unique
	 */
	public function setUnique(bool $unique){
		$this -> unique = $unique;
	}

	/**
	 * @param bool $null
	 */
	public function setNull(bool $null){
		$this -> null = $null;
	}

	/**
	 * @param string $default
	 */
	public function setDefault(string $default){
		$this -> default = $default;
	}

	/**
	 * @param int $length
	 */
	public function setLength(int $length = null){
		$this -> length = $length;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type){
		$this -> type = $type;
	}

	/**
	 * @return string
	 */
	public function get(){
		return $this;
	}

	/**
	 * Is equals to another column?
	 *
	 * @param SchemaColumn $c
	 * @return bool
	 */
	public function equals(SchemaColumn $c){
		return $this -> getType() == $c -> getType()
		&& $this -> getLength() == $c -> getLength()
		&& $this -> getAutoIncrement() == $c -> getAutoIncrement()
		&& $this -> getPrimary() == $c -> getPrimary()
		&& $this -> getIndex() == $c -> getIndex()
		&& $this -> getNull() == $c -> getNull()
		&& $this -> getName() == $c -> getName()
		&& $this -> equalsForeign($c);
	}

	/**
	 * Is equals foreign to another column?
	 *
	 * @param SchemaColumn $c
	 * @return bool
	 */
	public function equalsForeign(SchemaColumn $c){
		return
		$this -> getForeignTable() == $c -> getForeignTable()
		&& $this -> getForeignColumn() == $c -> getForeignColumn();
		/*
		&& $this -> getForeignDelete() == $c -> getForeignDelete()
		&& $this -> getForeignUpdate() == $c -> getForeignUpdate();
		*/
	}

	/**
	 * Reset foreign key
	 */
	public function resetForeign(){
		$this -> setConstraint(null);
		$this -> setForeign(null,null);
		$this -> setForeignDelete(null);
		$this -> setForeignUpdate(null);
	}

}