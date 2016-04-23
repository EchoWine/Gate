<?php

namespace CoreWine\DataBase;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Database, permits to handle connections and calls to DataBase with PDO
 */
class DB{


	/**
	 * Configuration
	 */
	private static $config;
	
	/**
	 * Connection
	 */
	public static $con;
	
	/**
	 * Log
	 */
	protected static $log = [];

	/**
	 * Contains the last ID of the table and it's used for restore
	 */
	public static $restoreLastID;

	/**
	 * Contains the last name of the table and it's used for restore
	 */
	public static $restoreLastTable;

	/**
	 * All information about schema/tables
	 */
	public static $schema = [];

	/**
	 * Enable/Disable log
	 */
	public static $enableLog = false;

	/**
	 * SQL
	 */
	public static $sql;
	
	/**
	 * Create a new connection
	 *
	 * @param array $cfg config
	 */
	public static function connect(array $cfg){

		self::$config = $cfg;
		
		try{

			self::$con = new PDO(
				$cfg['driver'].":host=".$cfg['hostname'].";charset=".$cfg['charset'],
				$cfg['username'],
				$cfg['password'],
				array(
					PDO::MYSQL_ATTR_LOCAL_INFILE => true,
					PDO::ATTR_TIMEOUT => 60,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				)
			);
			
		}catch(PDOException $e){
			self::printError("<b>You can't connect to the host</b><br>".$e->getMessage());
		}

		self::select($cfg['database']);

		switch($cfg['driver']){
			case 'mysql': self::$sql = 'MYSQL'; break;
		}

		Schema::ini();

		if($cfg['restore'] > 0)
			self::iniRestore();

		self::startLog();
	}
	
	/**
	 * @return SQL
	 */
	public static function SQL(){
		return __NAMESPACE__."\\".self::$sql;
	}
	
	/**
	 * Start log
	 */
	public static function startLog(){
		self::$enableLog = true;
	}

	/**
	 * End log
	 */
	public static function stopLog(){
		self::$enableLog = false;
	}

	/**
	 * Select the database
	 *
	 * @param string $db name of database
	 */
	public static function select(string $db){
		if(self::getAlterSchema())
			self::query("CREATE DATABASE IF NOT EXISTS $db");
		
		
		self::query("SET GLOBAL connect_timeout=500;");
		self::query("USE $db");
		self::query("set names utf8");

	}
	
	/**
	 * Close the connection of the database
	 */
	public static function close(){
		self::$con = NULL;
	}
	
	/**
	 * Return the name of the database
	 *
	 * @return string name database
	 */
	public static function getName(){
		return self::$config['database'];
	}

	/**
	 * Return the value of alter_schema
	 *
	 * @return string name database
	 */
	public static function getAlterSchema(){
		return self::$config['alter_schema'];
	}
	
	/**
	 * Return information about the database
	 *
	 * @return string information about the database
	 */
	public static function getServerInfo(){
		return 
			self::$con -> getAttribute(PDO::ATTR_DRIVER_NAME)." ".
			self::$con -> getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	/**
	 * Add characters of escape on the string for a query
	 *
	 * @return $s (string) string to filtrate
	 * @return string string to filtrate
	 */
	public static function escape(string $s){
		$s = str_replace("_","\_",$s);
		$s = str_replace("%","\%",$s);
		return $s;
	}

	/**
	 * Execute the query
	 *
	 * @param string $query SQL code
	 * @return object PDO object
	 */
	public static function query(string $query){

		try{
			$r = self::$con -> query($query);

			if(self::$enableLog)
				self::$log[] = "<i>".$query."</i>";

		}catch(PDOException $e){
			self::printError("<b>Query</b>: <i>$query</i><br>".$e -> getMessage());
		}

		if(!$r)
			self::printError("<b>Query</b>: <i>$query</i><br>".self::$con -> errorInfo()[2]."");
		
		

		return $r;
	}

	/**
	 * Execute the query with specific values to filtrate
	 *
	 * @param string $query SQL code
	 * @param array $a array of values
	 * @return object PDO object
	 */
	public static function execute(string $query,array $a){
		
		// Converto la query in una stringa leggibile
		$r = array_reverse($a);
		$k = array_keys($r);
		$v = array_values($r);
		foreach($v as &$e)
			if(is_string($e))$e = "'{$e}'";

		$q = str_replace($k,$v,$query);


		try{

			$r = self::$con -> prepare($query);
			$r -> execute($a);

			if(self::$enableLog)
				self::$log[] = "<i>".$q."</i>";

		}catch(PDOException $e){
			self::printError("<b>Query</b>: <i>$q</i><br>".$e -> getMessage());
		}

		if(!$r)
			self::printError("<b>Query</b>: <i>$q</i><br>".self::$con -> errorInfo()[2]."");
		

		return $r;
	}
	
	/**
	 * Execute the query and return a result as array
	 *
	 * @param PDOStatement|SQL code $q PDO object
	 * @return array result
	 */
	public static function fetch($q,$nIndex = true){
		if(is_string($q))$q = DB::query($q);

		return $nIndex ? $q -> fetchAll() : $q -> fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Execute the query and return the first result
	 *
	 * @param PDOStatement|SQL code $q PDO object
	 * @return array result
	 */
	public static function first($q){
		return DB::fetch($q)[0];
	}


	/**
	 * Count the results of a query
	 *
	 * @param PDOStatement $q PDO object
	 * @return int number of result
	 */
	public static function count(PDOStatement $q){
		return $q -> rowCount();
	}
	
	/**
	 * Print the log
	 */
	public static function printLog(){
		echo "<h1>DataBase Log</h1>";
		echo implode(self::$log,"<br>");
	}
	
	/**
	 * Print the error
	 *
	 * @param string $error body of the error
	 */
	private static function printError(string $error){
		self::printLog();
		die("<h1>DataBase Error</h1>$error<br>");
	}
		
	/**
	 * Get the value of the last field AUTO_INCREMENT insert
	 *
	 * @return int last value of the field AUTO_INCREMENT
	 */
	public static function getInsertID(){
		return self::$con -> lastInsertId();
	}
	
	/**
	 * Begin transaction
	 */
	public static function beginTransaction(){
		return self::$con -> beginTransaction();
	}

	/**
	 * Commit
	 */
	public static function commit(){
		return self::$con -> commit();
	}

	/**
	 * Rollback
	 */
	public static function rollback(){
		return self::$con -> rollback();
	}

	/**
	 * Transaction
	 *
	 * @param Closure $f
	 */
	public static function transaction(Closure $f){

		self::beginTransaction();

		try{
			$f();
			self::commit();
			return true;

		}catch(Exception $e){
			self::rollback();
			return false;

		}
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	//		RESTORE: PREPARE, SAVE, UNDO
	// 
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	/**
	 * Create the table that will handle the restore
	 */
	private static function iniRestore(){

		if(!self::$config['alter_schema'])return;

		$r = DB::schema('db_restore');
		$r -> id() -> alter();
		$r -> string('table_restore') -> alter();
		$r -> string('table_from') -> alter();


		self::confirm();
	}

	/**
	 * Save the table 
	 * Save the current status of the table
	 *
	 * @param string $table name of the table
	 */
	public static function save(string $table){

		do{
			$name = md5(microtime());
			$name = "db_restore_{$name}";
		}while(false);

		self::query("INSERT INTO db_restore (table_restore,table_from) VALUES ('{$name}','{$table}')");
		self::$restoreLastID = self::getInsertID();
		self::query("CREATE TABLE {$name} LIKE {$table}");
		self::query("INSERT {$name} SELECT * FROM {$table}");
		self::$restoreLastTable = $name;
	}

	/**
	 * Save the operation
	 * Delete the last operation and "confirm" the actual already entered
	 */
	public static function confirm(){
		
		
		$l = self::$config['restore'] - 1;
		$q = self::query("SELECT table_restore FROM db_restore ORDER BY id ASC");
		if(self::count($q) > self::$config['restore']){
			$a = $q -> fetch();
			self::query("DROP table {$a['table_restore']}");
			self::query("DELETE FROM db_restore WHERE table_restore = '{$a['table_restore']}'");
		}
	}

	/**
	 * Bring back the records of a table before the save
	 */
	public static function undo(){
		$table = self::$restoreLastTable;
		
		$q = self::query("SELECT * FROM db_restore WHERE table_restore = '{$table}'");
		$a = $q -> fetch();
		self::copy($a['table_from'],$a['table_restore']);

		self::query("DROP TABLE {$table}");
		self::query("DELETE FROM db_restore WHERE table_restore = '{$table}'");
	}

	/**
	 * Execute a restore
	 *
	 * @param int $n number of operations to going back
	 * @param int $id ID of the operation from which start
	 * @return bool result of the operation
	 */

	public static function restore(int $n = 1,int $id = NULL){

		if($n < 1) $n = 1;
		$n--;

		$w = isset($id) && !empty($id) ? "WHERE id = {$id} ORDER BY id DESC " : " ORDER BY id DESC LIMIT {$n},1";

		$q = self::query("SELECT * FROM db_restore {$w}");
		if(self::count($q) == 1){
			$a = $q -> fetch();
			
			self::save($a['table_from']);
			
			$q = self::copy($a['table_from'],$a['table_restore']);

			self::confirm();
			return $q;
		}
		return false;
	}

	/**
	 * Copy the content of a table in another
	 *
	 * @param string $t1 name of table to restore
	 * @param stirng $t2 name of table to take data
	 * @return bool result of the query
	 */
	private static function copy(string $t1,string $t2){

		return self::query("TRUNCATE table {$t1}") && 
		self::query("INSERT {$t1} SELECT * FROM {$t2}");
	}

	/**
	 * Drop all undeclared columns
	 */
	public static function dropMissing(){
		Schema::dropMissing();
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// 
	//		Builders
	// 
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	/**
	 * Create a new object QueryBuilder
	 *
	 * @param string|array|closure $table
	 * @return object QueryBuilder object
	 */
	public static function table($table,$alias = NULL){
		return new QueryBuilder($table,$alias);
	}

	/**
	 * Create a new object SchemaBuilder
	 *
	 * @param string $table
	 * @param closure $columns
	 * @return object SchemaBuilder object
	 */
	public static function schema($table,$columns = null){
		return new SchemaBuilder($table,$columns);
	}

}
?>
