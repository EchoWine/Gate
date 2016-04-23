<?php

namespace CoreWine\DataBase;

/**
 * SQL
 */
class SQL{

	const TINYINT = 'tinyint';
	const BIGINT = 'bigint';
	const INT = 'int';
	const VARCHAR = 'varchar';
	const FLOAT = 'float';
	const DOUBLE = 'double';
	const TEXT = 'text';


	const COUNT = 'COUNT';
	const MAX = 'MAX';
	const MIN = 'MIN';
	const AVG = 'AVG';
	const SUM = 'SUM';

	const AND = 'AND';
	const OR = 'OR';


	const INDEX = 'index';
	const FOREIGN = 'foreign';


	const LEFT_JOIN = 'LEFT JOIN';
	const RIGHT_JOIN = 'RIGHT JOIN';
	const CROSS_JOIN = 'CROSS JOIN';
	const JOIN = 'JOIN';

	const SELECT_ALL = '*';

	/**
	 * @return string get all the tables
	 */
	public static function SHOW_TABLES(){
		return "SHOW TABLES";
	}

	/**
	 * @param string $tableName
	 * @return string get information about a table
	 */
	public static function SHOW_TABLE($tableName){
		return "describe $tableName";
	}

	/**
	 * @param string $tableName
	 * @return string get all index in a table
	 */
	public static function SHOW_INDEX($tableName){
		return "SHOW INDEX FROM $tableName";
	}

	/**
	 * @param string $dbName
	 * @param string $tableName
	 * @return string get all constraint in a table
	 */
	public static function SHOW_CONSTRAINT($dbName,$tableName){
		return "
			select TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME 
			from information_schema.key_column_usage 
			WHERE CONSTRAINT_SCHEMA = '$dbName' AND TABLE_NAME = '$tableName' AND REFERENCED_TABLE_NAME IS NOT NULL
		";
	}

	public static function DROP_TABLE($tableName){
		return "DROP TABLE $tableName";
	}

	public static function DROP_COLUMN($tableName,$columnName){
		return "ALTER TABLE $tableName DROP COLUMN $columnName";
	}

	public static function DROP_FOREIGN_KEY($tableName,$constraintName){
		return "ALTER TABLE $tableName DROP FOREIGN KEY $constraintName";
	}

	public static function MODIFY_COLUMN_RESET($tableName,$columnName){
		return "ALTER TABLE $tableName MODIFY $columnName tinyint(1)";
	}

	public static function DROP_PRIMARY_KEY($tableName){
		return "ALTER TABLE $tableName DROP PRIMARY KEY";
	}

	public static function DROP_INDEX_KEY($tableName,$indexName){
		return "ALTER TABLE $tableName DROP INDEX $indexName";
	}

	public static function ADD_INDEX_KEY($tableName,$indexName){
		return "ALTER TABLE $tableName ADD INDEX($indexName)";
	}

	public static function ADD_FOREIGN_KEY($tableName,$column,$constraintName,$foreignTable,$foreignColumn,$onDelete,$onUpdate){
 		
 		$onDelete =  $onDelete ? ' ON DELETE '.$onDelete : '';
 		$onUpdate =  $onUpdate ? ' ON UPDATE '.$onUpdate : '';
 		$constraintName = $constraintName != null ? ' CONSTRAINT '.$constraintName : '';

		return "ALTER TABLE $tableName ADD 
			$constraintName 
			FOREIGN KEY ($column)
			REFERENCES $foreignTable($foreignColumn)
			$onDelete $onUpdate
		";

	}
	public static function SELECT_CONSTRAINT($dbName,$tableName,$columnName){
		return "
			select CONSTRAINT_NAME 
			from information_schema.key_column_usage 
			WHERE CONSTRAINT_SCHEMA = '$dbName' AND TABLE_NAME = '$tableName' AND COLUMN_NAME = '$columnName'
		";
	}

	public static function CREATE_TABLE($tableName,$columns){
		return "CREATE TABLE IF NOT EXISTS $tableName (".implode(",",$columns).")";
	}

	public static function EDIT_COLUMN($tableName,$columnName,$column){
		return "ALTER TABLE $tableName CHANGE COLUMN $columnName $column";
	}

	public static function ADD_COLUMN($tableName,$column){
		return "ALTER TABLE $tableName ADD $column";
	}

	public static function COLUMN($name,$type,$length = null,$default = null,$primary = false,$unique = false,$auto_increment = false,$null = false){

		$unique = $unique ? 'UNIQUE' : '';
		$primary = $primary ? 'PRIMARY KEY' : '';
		$auto_increment = $auto_increment ? 'AUTO_INCREMENT' : '';
		$null = $null ? 'NULL' : 'NOT NULL';
		$default = $default != null ? ' DEFAULT '.(is_string($default) ? "'$default'" : $default) : '';

		return $name." ".self::TYPE($type,$length)." $default $primary $auto_increment $unique $null";
	}

	public static function TYPE($type,$length){

		switch($type){

			case self::TINYINT:
			case self::INT:
			case self::BIGINT:
			case self::VARCHAR:
			case self::FLOAT:
			case self::DOUBLE:
				return "$type($length)";


			case self::TEXT:
				return $type;

		}

		die('Error');
	}

	public static function ENABLE_CHECKS_FOREIGN(){
		return "SET FOREIGN_KEY_CHECKS = 1";
	}

	public static function DISABLE_CHECKS_FOREIGN(){
		return "SET FOREIGN_KEY_CHECKS = 0";
	}
	
	public static function AGGREGATE($function,$value){
		return "$function($value)";
	}

	public static function ASC($column){
		return "$column ASC";
	}	

	public static function DESC($column){
		return "$column DESC";
	}	

	public static function GROUP_BY($columns){
		return empty($columns) ? '' : ' GROUP BY '.implode(' , ',$columns);
	}

	public static function ORDER_BY($columns){
		return empty($columns) ? '' : ' ORDER BY '.implode(' , ',$columns);
	}	

	public static function LIMIT($skip = null,$take = null){
		$skip = $skip !== null ? $skip."," : "";
		$take = $take !== null ? $take : "";
		return empty($s) && empty($t) ? "" : "LIMIT {$s}{$t}";
	}

	public static function COL_OP_VAL($col,$op,$val){
		return "$col $op $val";
	}

	public static function IN($col,$val){
		return "$col IN (".implode(",",$val).")";
	}

	public static function LIKE($col,$val){
		return "$col LIKE $val";
	}

	public static function IS_NULL($col){
		return "$col IS NULL";
	}

	public static function IS_NOT_NULL($col){
		return "$col IS NOT NULL";
	}

	public static function AND($exp){
		return !empty($exp) ? (count($exp) > 1 ? "(" : "").implode(" AND ",$exp).(count($exp) > 1 ? ")" : "") : '';
	}

	public static function OR($exp){
		return !empty($exp) ? (count($exp) > 1 ? "(" : "").implode(" OR ",$exp).(count($exp) > 1 ? ")" : "") : '';
	}

	public static function WHERE($val){
		return !empty($val) ? " WHERE $val" : '';
	}

	public static function INCREMENT($column,$value){
		return "$column = $column + $value";
	}
	public static function DECREMENT($column,$value){
		return "$column = $column + $value";
	}

	public static function REMOVE_ALIAS($table){
		return str_replace(' ','',explode("as",$table)[0]);
	}

	public static function ALIAS_FROM($val,$alias){
		return "($val) as $alias";
	}

	public static function GET_ALIAS($table){
		$r = explode("as",$table);
		return [str_replace(' ','',$r[0]),isset($r[1]) ? str_replace(' ','',$r[1]) : str_replace(' ','',$r[0])];
	}

	public static function JOIN($type,$table,$on){
		return "$type $table ".self::ON($on)."";
	}

	public static function JOINS($joins){
		return implode(" ",$joins);
	}

	public static function ON($exp){
		return !empty($exp) ? " ON $exp" : '';
	}

	public static function HAVING($exp){
		return !empty($exp) ? " HAVING $exp" : '';
	}

	public static function UNION($select){
		return implode(" UNION ",$select);
	}

	public static function ANNIDATE_FROM($from){
		return "($from)";
	}

	public static function SELECT($columns,$from,$exp){

		if(empty($columns))$columns[] = self::SELECT_ALL;

		return "SELECT ".implode(",",$columns)." FROM $from $exp";
	}

	public static function VALUES($values){
		return is_array($values) ? "(".implode($values,",").")" :  "($values)";
	}

	public static function INSERT($table,$columns,$values,$ignore = false){
		return "INSERT ".($ignore ? 'IGNORE' : '')." INTO $table $columns $values";
	}

	public static function INSERT_VALUES($values){
		return "VALUES ".implode($values,",");
	}

	public static function INSERT_COLUMNS($columns){
		return empty($columns) ? '' : "(".implode($columns,",").")";
	}

	public static function UPDATE_VALUE($column,$value){
		return "$column = $value";
	}
	public static function UPDATE($table,$join,$set,$where){
		return "UPDATE $table $join SET ".implode(",",$set)." $where";
	}

	public static function UPDATE_WHEN($when,$then){
		return " WHEN $when THEN $then ";
	}

	public static function UPDATE_CASE($column,$case,$default,$when){
		return "$column = CASE $case ".implode(" ",$when)." ELSE $default END";
	}

	public static function RESET_AUTOINCREMENT($tableName){
		return "ALTER TABLE {$tableName} AUTO_INCREMENT = 1";
	}

	public static function DELETE($delete,$nameTable,$join,$where){
		return "DELETE $delete FROM $nameTable $join $where";
	}

	public static function BETWEEN($column,$value_from,$value_to){
		return "$column BETWEEN $value_from AND $value_to";
	}

	public static function NOT_BETWEEN($column,$value_from,$value_to){
		return "$column NOT BETWEEN $value_from AND $value_to";
	}
}