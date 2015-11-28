<?php

/**
 * @class DB
 * Classe Database, permette di gestire la connessione e le chiamate al DBMS tramite PDO
 */
class DB{


	/**
	 * Configurazione
	 */
	public static $config;
	
	/**
	 * Connessione
	 */
	public static $con;
	
	/**
	 * Log
	 */
	protected static $log;

	/**
	 * Oggetto contente informazioni per le query semplificate
	 */
	public static $exe;

	/**
	 * Oggetto per schema
	 */
	public static $schema;

	/**
	 * Ultimo ID tab salvato
	 */
	public static $save_id;

	/**
	 * Ultimo nome tab salvato
	 */
	public static $save_name;
	
	/**
	 * Crea una nuova connessione
	 */
	public static function connect(){

		try{

			self::$con = new PDO(
				self::$config['driver'].":host=".self::$config['hostname'].";charset=".self::$config['charset'],
				self::$config['username'],
				self::$config['password'],
				array(
					PDO::MYSQL_ATTR_LOCAL_INFILE => true,
					PDO::ATTR_TIMEOUT => 60,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				)
			);
			
		}catch(PDOException $e){
			self::printError("<b>You can't connect to the host</b><br>".$e->getMessage());
			die();
		}

		self::selectDB(self::$config['database']);
		self::iniRollback();

	}
	
	/**
	 * Seleziona il database
	 * @param $db (string) nome database
	 */
	public static function selectDB($db){
		if(self::$config['alter_schema'])
			self::query("CREATE DATABASE IF NOT EXISTS $db");
		
		
		self::query("SET GLOBAL connect_timeout=500;");
		self::query("USE $db");
		self::query("set names utf8");

	}
	
	/**
	 * Chiude la connessione con il database
	 */
	public static function close(){
		self::$con = NULL;
	}
	
	/**
	 * Esegue la query
	 * @param $query (string) codice SQL
	 * @return (object) oggetto PDO
	 */
	public static function query($query){

		try{
			$r = self::$con -> query($query);

		}catch(PDOException $e){
			self::printError("<b>Query</b>: <i>$query</i><br>".$e -> getMessage());
			error_backtrace();
			die();
		}

		if(!$r){
			self::printError("<b>Query</b>: <i>$query</i><br>".self::$con -> errorInfo()[2]."");
			error_backtrace();
			die();
		}
		
		self::$log[] = "<i>".$query."</i>";

		return $r;
	}

	/**
	 * Esegue la query con determinati valori da filtrare
	 * @param $query (string) codice SQL
	 * @param $a (array) array di valori
	 * @return (object) oggetto PDO
	 */
	public static function execute($query,$a){
		
		try{

			$r = self::$con -> prepare($query);
			$r -> execute($a);
			

		}catch(PDOException $e){
			self::printError("<b>Query</b>: <i>$query</i> <br><b>Value</b>: <i>".json_encode($a)."</i><br>".$e -> getMessage());
			error_backtrace();
			die();
		}

		if(!$r){
			self::printError("<b>Query</b>: <i>$query</i><br>".self::$con -> errorInfo()[2]."");
			error_backtrace();
			die();
		}


		self::$log[] = "<i>".$query." (".json_encode($a).")</i>";
		return $r;
	}
	
	/**
	 * Esegue la query e restituisce un risultato leggibile come array
	 * @param $query (string) codice SQL
	 * @return (array) risultato
	 */
	public static function fetch($query){
		$c = self::query($query);
		return $c -> fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Esegue la query con dei valori da filtrare e restituisce un risultato leggibile come array
	 * @param $query (string) codice SQL
	 * @param $value (array) array di valori
	 * @return (array) risultato
	 */
	public static function executeAndfetch($query,$value){
		$c = self::execute($query,$value);
		return $c -> fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Stampa i log
	 */
	public static function printLog(){
		$log = new log("Query's Log");
		$log -> setLog(self::$log);
		$log -> print_();
	}
	
	/**
	 * Stampa l'errore
	 * @param $error (string) contenuto dell'errore
	 */
	private static function printError($error){
		echo "<h1>DataBase error</h1>";
		echo $error;
	}
	
	/**
	 * Controlla l'esistenza di una tabella
	 * @param $name (string) nome della tabella
	 * @return (bool) la tabella esiste (true) o meno (false)
	 */
	public static function if_table_exists($name){
		return (self::count(self::query("SHOW TABLES LIKE '$name'")) == 1);
	}
		
	/**
	 * Conta i risultati di una query
	 * @param $q (object) oggetto PDO
	 * @return (int) numero risultati
	 */
	public static function count($q){
		return $q -> rowCount();
	}

	/**
	 * Restituisce il nome del database
	 * @return (string) nome database
	 */
	public static function getName(){
		return self::$config['database'];
	}
	
	/**
	 * Esegue la funzione di escape
	 * @param $s (string) stringa da filtrare
	 * @return (string) stringa filtrata
	 */
	public static function quote($s){
		return $s;
	}

	/**
	 * Ottiene il valore dell'ultimo campo AUTO_INCREMENT inserito
	 * @return (int) ultimo valore del campo AUTO_INCREMENT
	 */
	public static function insert_id(){
		return self::$con -> lastInsertId();
	}

	/**
	 * Restituisce informazioni sul database
	 * @return (string) informazioni sul database
	 */
	public static function get_server_info(){
		return 
			self::$con -> getAttribute(PDO::ATTR_DRIVER_NAME)." ".
			self::$con -> getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	/**
	 * Aggiunge caratteri di escape alla stringa per una query
	 * @return $s (string) stringa da filtrare
	 * @return (string) stringa filtrata
	 */
	public static function escapeQuery($s){
		$s = str_replace("_","\_",$s);
		$s = str_replace("%","\%",$s);
		return $s;
	}

	/**
	 * Crea la tabella che gestisce il rollback
	 */
	public static function iniRollback(){

		if(!self::$config['alter_schema'])return;
		
		self::query("
			CREATE TABLE IF NOT EXISTS database_rollback(
				id BIGINT(11) auto_increment,
				table_rollback varchar(55),
				table_from varchar(55),
				primary key (id)
			);
		");

		self::_delete();
	}

	/**
	 * Salva lo status attuale della tabella
	 * @param $table (string) nome tabella
	 */
	public static function save($table){
		self::$save_name = self::_save($table);
	}

	/**
	 * Conferma l'ultima operazione 
	 */
	public static function commit(){
		self::_delete();
	}

	/**
	 * Torna indietro di un'operazione
	 */
	public static function undo(){
		$table = self::$save_name;
		self::_rollback();
		self::query("DROP TABLE {$table}");
		self::query("DELETE FROM database_rollback WHERE table_rollback = '{$table}'");
	}

	/**
	 * Salva una tabella
	 * @param $table (string) nome tabella
	 */
	public static function _save($table){
		// Salvo i dati...	
		do{
			$name = md5(microtime());
			$name = "database_rollback_{$name}";
		}while(false);

		self::query("INSERT INTO database_rollback (table_rollback,table_from) VALUES ('{$name}','{$table}')");
		self::$save_id = self::insert_id();
		self::query("CREATE TABLE {$name} LIKE {$table}");
		self::query("INSERT {$name} SELECT * FROM {$table}");

		return $name;

	}
	
	/**
	 * Elimina l'ultima operazione salvata
	 */
	public static function _delete(){

		// Cancellare l'ultima istanza
		$l = self::$config['rollback'] - 1;
		$q = self::query("SELECT table_rollback FROM database_rollback ORDER BY id ASC");
		if(self::count($q) > self::$config['rollback']){
			$a = $q -> fetch();
			self::query("DROP table {$a['table_rollback']}");
			self::query("DELETE FROM database_rollback WHERE table_rollback = '{$a['table_rollback']}'");
		}

	}

	/**
	 * Effettua un rollback
	 */
	public static function _rollback(){
		$table = self::$save_name;
		$q = self::query("SELECT * FROM database_rollback WHERE table_rollback = '{$table}'");
		$a = $q -> fetch();
		self::query("TRUNCATE table {$a['table_from']}");
		self::query("INSERT {$a['table_from']} SELECT * FROM {$a['table_rollback']}");
	}

	/**
	 * Predispone tutto per un rollback
	 * @param $n (int) numero di operazioni da cui tornare indietro
	 * @param $id (int) identificatore dell'operazione da cui partire
	 * @param $overwrite (bool) sovrascrivi i record durante il rollback
	 * @return (bool) risultato dell'operazione
	 */
	public static function rollback($n = 1,$id = NULL,$overwrite = true){

		if($n < 1) $n = 1;
		$n--;

		$w = isset($id) && !empty($id) ? "WHERE id = {$id} ORDER BY id DESC " : " ORDER BY id DESC LIMIT {$n},1";

		$q = self::query("SELECT * FROM database_rollback {$w}");
		if(self::count($q) == 1){
			$a = $q -> fetch();
			
			self::_save($a['table_from']);
			
			if($overwrite){
				$q1 = self::query("TRUNCATE table {$a['table_from']}");
				$q2 = self::query("INSERT {$a['table_from']} SELECT * FROM {$a['table_rollback']}");
			}else{
				$q1 = true;
				$q2 = self::query("
					INSERT IGNORE {$a['table_from']} 
					SELECT * FROM {$a['table_rollback']} 
					ON DUPLICATE KEY UPDATE {$a['table_from']}.id = {$a['table_from']}.id
				");
			}

			self::_delete();
			return $q1 && $q2;
		}
		return false;
	}

	/**
	 * Controlla l'esistenza di una tabella o di una colonna
	 * @param $w (string) indica se si tratta di una colonna o di una tabella
	 * @param $t (string) nome della tabella
	 * @param $n (string) optional nome della colonna
	 * @return (bool) l'oggetto ricercato esiste (true)
	 */
	public static function exists($w,$t,$n = ''){
		switch($w){
			case 'column':
				$q = self::query("
					SELECT * FROM information_schema.COLUMNS  WHERE 
					TABLE_SCHEMA = '{self::getName()}' AND 
					TABLE_NAME = '{$t}' AND
					COLUMN_NAME = '{$n}'
				");
				return self::count($q) == 1;
			break;
			case 'table':
				return self::if_table_exists($t);
			break;
		}				
	}

	/**
	 * Controlla l'esistenza di una tabella
	 * @param $v (string) nome della tabella
	 * @return (bool) restituisce se la tabella esiste (true) o meno (false)
	 */
	public static function hasTable($v){
		return self::count(self::query("SHOW TABLES LIKE '{$v}'")) == 1;
	}

	/**
	 * Controlla l'esistenza di una colonna
	 * @param $v1 (string) nome della tabella
	 * @param $v2 (string) nome della colonna
	 * @return (bool) restituisce se la colonna esiste (true) o meno (false)
	 */
	public static function hasColumn($v1,$v2){
		return self::table('information_schema.COLUMNS')
			-> where('TABLE_SCHEMA',self::getName())
			-> where('TABLE_NAME',$v1)
			-> where('COLUMN_NAME',$v2)
			-> count() == 1;
	}

	/**
	 * Crea un nuovo oggetto queryBuilder
	 * @param $v (string) nome tabella
	 * @param $as (string) alias tabella
	 * @return (object) oggetto queryBuilder
	 */
	public static function table($v,$as = ''){
		return new queryBuilder($v,$as);
	}

}

?>