<?php

namespace CoreWine\DataBase;

/**
 * Schema
 */
class Schema{

	/**
	 * List of all table
	 */
	public static $tables = [];

	/**
	 * Initialization
	 */
	public static function ini(){		

		# Get info about all tables
		foreach(DB::fetch(DB::SQL()::SHOW_TABLES()) as $k){

			$table = new SchemaTable($k[0]);
			
			# Get columns
			foreach(DB::fetch(DB::SQL()::SHOW_TABLE($table -> getName())) as $k){
				
				preg_match('/\((.*)\)/',$k['Type'],$length);
				$type = preg_replace('/\((.*)\)/','',$k['Type']);

				$column = new SchemaColumn([
					'table' => $table -> getName(),
					'name' => $k['Field'],
					'type' => $type,
					'length' => isset($length[1]) ? $length[1] : null,
					'null' => $k['Null'] == 'YES',
					'default' => $k['Default'],
					'primary' => $k['Key'] == 'PRI',
					'unique' => $k['Key'] == 'UNI',
					'auto_increment' => $k['Extra'] == 'auto_increment',
				]);

				$table -> addColumn($column);

			}

			# Get index
			foreach(DB::fetch(DB::SQL()::SHOW_INDEX($table -> getName())) as $k){
				if(!$table -> getColumn($k['Column_name']) -> getPrimary())
					$table -> getColumn($k['Column_name']) -> setIndex($k['Key_name']);
				
			}

			foreach(DB::fetch(DB::SQL()::SHOW_CONSTRAINT(DB::getName(),$table -> getName())) as $k){

				$c = $table -> getColumn($k['COLUMN_NAME']);

				$c -> setConstraint($k['CONSTRAINT_NAME']);
				$c -> setForeign($k['REFERENCED_TABLE_NAME'],$k['REFERENCED_COLUMN_NAME']);

			}

			self::$tables[$table -> getName()] = $table;
		}

	}

	/**
	 * Drop all the table/column that aren't defined
	 */
	public static function dropMissing(){
		foreach(self::$tables as $n => $k){
			if(!isset(SchemaBuilder::$tables[$n]))
				DB::schema($n) -> drop();

			else{
				$table = SchemaBuilder::$tables[$n];

				foreach($k -> getColumns() as $name_column => $column){
					if($table -> getColumn($name_column) == null){
						DB::schema($n) -> dropColumn($name_column);
					}
				}

				
				
			}

		}
	}

	/**
	 * @param string $tableName
	 * @return bool exists table
	 */
	public static function hasTable($tableName){
		return isset(self::$tables[$tableName]);
	}

	/**
	 * @param string $tableName
	 * @param string $columName
	 * @return bool exists column
	 */
	public static function tableHasColumn($tableName,$columnName){
		return self::$tables[$tableName] -> hasColumn($columnName);
	}

	/**
	 * @return bool cont all columns
	 */
	public static function tableCountColumns($table,$column){
		return self::hasTable($table) ? self::$table[$table] -> countColumns() : 0;
	}

	/**
	 * @return SchemaTable[] array 
	 */
	public static function getTables(){
		return self::$tables;
	}

	/**
	 * @param string $tableName
	 * @return SchemaTable table
	 */
	public static function getTable($tableName){
		return isset(self::$tables[$tableName]) ? self::$tables[$tableName] : null;
	}

	/**
	 * Add a table
	 * 
	 * @param string $tableName
	 * @param array $columns
	 */
	public static function addTable($tableName,$columns = []){
		$table = new SchemaTable($tableName,$columns);
		self::$tables[$table -> getName()] = $table;
	}

	/**
	 * Get all columns that have a foreign key related to the table 
	 * 
	 * @param string $tableName
	 */
	public static function getAllForeignKeyTo($tableName){
		$r = [];
		foreach(self::$tables as $n => $k){
			$c = $k -> getForeignKeyTo($tableName);
			if($c !== null)$r[] = $c;
		}

		return $r;
	}

	/**
	 * Get all columns that have a foreign key related to the table and column 
	 * 
	 * @param string $tableName
	 * @param string $columnName
	 */
	public static function getAllForeignKeyToColumn($tableName,$columnName){
		$r = [];
		foreach(self::$tables as $n => $k){
			$c = $k -> getForeignKeyToColumn($tableName,$columnName);
			if($c !== null)$r[] = $c;
		}

		return $r;
	}

}