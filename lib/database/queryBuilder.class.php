<?php

/**
 * @class queryBuilder
 * Classe che permette la gestione delle query in maniera ben definita e semplificata
 */
class queryBuilder{

	/**
	 * Informazioni riguardanti la costruzione della query
	 */
	public $builder;

	/**
	 * Informazioni riguardanti l'alterazione dello schema del DB
	 */
	public $schema;

	/**
	 * Lista di tutti i nomi delle tabelle la quale esistenza è già stata verificata
	 */
	public static $cacheAlter = array();

	/**
	 * Lista di tutti gli alias creati automaticamente per le select annidate
	 */
	public static $tableAs = array();

	/**
	 * Inizializza l'oggetto, viene richiamata dal metodo table della classe DataBase 
	 * @param $v (string) nome della tabella
	 * @param $as (string) optional alias della tabella
	 * @return (string) nome alias della tabella
	 */
	public function __construct($v,$as = ''){

		$this -> builder = new stdObject();
		$this -> builder -> prepare = array();

		/* Controllo che si tratti di una select annidata */
		if(is_closure($v)){
			$t = $v();
			$v = "(".$t -> getSelectSQL().")";
			if(empty($as)) $as = self::getTableAsRandom();
			$this -> builder -> prepare = $t -> builder -> prepare;
		}

		$this -> builder -> table = $v;
		$this -> builder -> table_as = $as;
		$this -> builder -> agg = array();
		$this -> builder -> select = array();
		$this -> builder -> update = array();
		$this -> builder -> orderby = array();
		$this -> builder -> skip = NULL;
		$this -> builder -> take = NULL;
		$this -> builder -> groupBy = array();;
		$this -> builder -> andWhere = array();
		$this -> builder -> orWhere = array();
		$this -> builder -> join = array();
		$this -> builder -> is_table = false;
		$this -> builder -> indexArrayResult = "";
		$this -> builder -> tmp_prepare = array();

		return $this;
	}
	
	/**
	 * Restituisce un nome random(che non è stato ancora usato) da usare come alias per le query 
	 * @return (string) nome alias della tabella
	 */
	public static function getTableAsRandom(){
		$c = "t".count(self::$tableAs);
		self::$tableAs[] = $c;
		return $c;
	}

	/**
	 * Clona l'attributo builder
	 */
	public function __clone(){
		$this -> builder = clone $this -> builder;
	}

	/**
	 * Esegue la query
	 * @return (object) risultato della query
	 */
	public function query($q,$p = NULL){
		if(!isset($p))$p = $this -> builder -> prepare;

		return empty($p) ? DB::query($q) : DB::execute($q,$p);
		
	}

	/**
	 * Esegue la query e converte il risultato in un array
	 * @param $q (string) query da eseguire
	 * @param $p (array) array di valori da preparare
	 * @return (array) risultato della query
	 */
	public function assoc($q,$p = NULL){
		if(!isset($p))$p = $this -> builder -> prepare;
		return empty($p) ? DB::fetch($q) : DB::executeAndfetch($q,$p);
	}

	/**
	 * Prepara un valore per essere immesso nel codice SQL. Usato nelle chiamte PDO
	 * @param $v (string) valore
	 * @return (string) nome del valore
	 */
	public function setPrepare($v){
		$l = ":p".count($this -> builder -> prepare);
		$this -> builder -> prepare[$l] = $v;
		return $l;
	}

	/**
	 * Esegue la query e ritorna se il record esiste o meno
	 * @param $v (string) nome della colonna
	 * @param $a (mixed) valore o array di valori che identificano la colonna
	 * @return (mixed) bool se è un solo valore o array di bool se è un array di record
	 */
	public function exists($v,$a){
		$r = is_array($a) ? $this -> whereIn($v,$a) : $this -> where($v,$a);
		$r = $r -> select($v);
		$r = is_array($a) ? $r -> setIndexArrayResult($v) -> lists() : $r -> get();

		if(is_array($a)){

			$r = array_keys($r);
			$t = array();
			foreach($a as $k){
				$t[$k] = in_array($k,$r) ? 1 : 0;
			}

			$r = $t;
		}	
		return is_array($a) ? $r : $r[$v];
	}

	/**
	 * Esegue la query e ritorna il numero dei record presenti nella tabella, se la colonna è specificata
	 * ritorna il numero dei record con il valore della colonna non nullo
	 * @param $v (string) nome della colonna
	 * @return (int) numero dei record
	 */
	public function count($v = '*'){
		return $this -> selectFunction($v,'COUNT');
	}

	
	/**
	 * Esegue la query e ritorna il valore minimo di una colonna specifica
	 * @param $v (string) nome della colonna
	 * @return (mixed) valore minimo dei valori di una colonna
	 */
	public function min($v){
		return $this -> selectFunction($v,'MIN');
	}

	/**
	 * Esegue la query e ritorna il valore massimo di una colonna specifica
	 * @param $v (string) nome della colonna
	 * @return (mixed) valore massimo dei valori di una colonna
	 */
	public function max($v){
		return $this -> selectFunction($v,'MAX');
	}

	/**
	 * Esegue la query e ritorna il valore medio di una colonna specifica
	 * @param $v (string) nome della colonna
	 * @return (float) valore medio dei valori di una colonna
	 */
	public function avg($v){
		return $this -> selectFunction($v,'AVG');
	}

	/**
	 * Esegue la query e ritorna la somma dei valori di una colonna specifica
	 * @param $v (string) nome della colonna
	 * @return (float) somma dei valori di una colonna
	 */
	public function sum($v){
		return $this -> selectFunction($v,'SUM');
	}
	
	/**
	 * Esegue la query e ritorna il risultato di una funzione sui dei valori di una colonna specifica
	 * @param $v (string) nome della colonna
	 * @param $f (string) funzione
	 * @return (object) $this
	 */
	public function selectFunction($v,$f){
		$c = clone $this;
		$c -> builder -> select[] = "{$f}({$v})";
		$r = $c -> get();

		return isset($r["{$f}({$v})"]) ? $r["{$f}({$v})"] : 0;

	}

	/**
	 * Ordina i risultati per ordine crescente
	 * @param $v (string) nome della colonna
	 * @return (object) $this
	 */
	public function orderByAsc($c){
		$this -> builder -> orderby[] = "$c ASC";
		return $this;
	}

	/**
	 * Ordina i risultati per ordine decrescente
	 * @param $v (string) nome della colonna
	 * @return (object) $this
	 */
	public function orderByDesc($c){
		$this -> builder -> orderby[] = "$c DESC";
		return $this;
	}

	/**
	 * Restituisce il codice SQL per l'ordinamento
	 * @return (string) codice SQL
	 */
	public function getOrderBySQL(){
		$o = $this -> builder -> orderby;
		return empty($o) ? '' : ' ORDER BY '.implode(' , ',$o);
	}

	/**
	 * Aggiunge alla query una colonna da selezionare
	 * @param $a (mixed) contiene la lista delle colonne da aggiungere o una singoloa colonna
	 * @return (object) $this
	 */
	public function select($a){
		if(is_array($a)){
			foreach($a as $k => $v){
				$this -> builder -> select[] = $v;
			}
		}else{
			$this -> builder -> select[] = $a;
		}
		return $this;
	}

	/**
	 * Salta un numero di risultati della query definito dal parametro
	 * @param $v (int) numero di risultati da saltare
	 * @return (object) $this
	 */
	public function skip($v){
		$this -> builder -> skip = (int)$v;
		return $this;
	}
	
	/**
	 * Prendi un numero di risultati della query definito dal parametro
	 * @param $v (int) numero di risultati da prendere
	 * @return (object) $this
	 */
	public function take($v){
		$this -> builder -> take = (int)$v;
		return $this;

	}
	
	/**
	 * Restituisce il codice SQL per selezionare un range di risultati definito da skip e take
	 * @return (string) codice SQL
	 */
	public function getLimitSQL(){
		$s = isset($this -> builder -> skip) ? $this -> builder -> skip."," : "";
		$t = isset($this -> builder -> take) ? $this -> builder -> take : "";
		return empty($s) && empty($t) ? "" : "LIMIT {$s}{$t}";
	}

	/**
	 * Aggiunge una condizione WHERE AND alla query dove i risultati devono avere un valore di una colonna
	 * ben specifico. Il risultato cambia a seconda dei parametri
	 * @param $v1 (mixed) Indica il nome della colonna o una closure eseguita per funzioni where avanzate. 
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto, altrimenti il valore colonna
	 * @param $v3 (string) optional valore della colonna
	 * @param $v4 (bool) ?? DA RIMUOVERE FORSE ??
	 * @return (object) $this
	 */
	public function where($v1,$v2 = NULL,$v3 = NULL,$v4 = true){

		// Se si tratta di un where avanzato
		if(is_closure($v1)){
			$n = DB::table($this -> builder -> table);
			$t = clone $this;
			$n -> builder -> prepare = $t -> builder -> prepare;
			$n = $v1($n);
			$sql = $n -> getWhereSQL(false);

			if(!empty($sql)){
				$t -> builder -> andWhere[] = $sql;
				$t -> builder -> prepare = $n -> builder -> prepare;
			}

			return $t;
		}

		return $this -> _where($v1,$v2,$v3,$v4,'AND');
	}

	/**
	 * Aggiunge una condizione WHERE OR alla query dove i risultati devono avere un valore di una colonna
	 * ben specifico. Il risultato cambia a seconda dei parametri
	 * @param $v1 (mixed) Indica il nome della colonna o una closure eseguita per funzioni where avanzate. 
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto, altrimenti il valore colonna
	 * @param $v3 (string) optional valore della colonna
	 * @param $v4 (bool) ?? DA RIMUOVERE FORSE ??
	 * @return (object) $this
	 */
	public function orWhere($v1,$v2 = NULL,$v3 = NULL,$v4 = true){

		// Se si tratta di un where avanzato
		if(is_closure($v1)){
			$n = DB::table($this -> builder -> table);
			$t = clone $this;
			$n -> builder -> prepare = $t -> builder -> prepare;
			$n = $v1($n);
			$sql = $n -> getWhereSQL(false);

			if(!empty($sql)){
				$t -> builder -> orWhere[] = $sql;
				$t -> builder -> prepare = $n -> builder -> prepare;
			}

			return $t;
		}

		return $this -> _where($v1,$v2,$v3,$v4,'OR');
	}

	/**
	 * Aggiunge una condizione WHERE alla query dove i risultati devono avere un valore di una colonna
	 * ben specifico. Il risultato cambia a seconda dei parametri
	 * @param $v1 (string) se $v2 è definito indica il nome colonna, altrimenti il valore della colonna primaria
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto, altrimenti il valore colonna
	 * @param $v3 (string) optional valore della colonna
	 * @param $v4 (bool) ?? DA RIMUOVERE FORSE ??
	 * @param $v5 (string) tipo di where AND|OR
	 * @return (object) clone di $this
	 */
	public function _where($v1,$v2 = NULL,$v3 = NULL,$v4 = true,$ao){
		$t = clone $this;

		if(isset($v3)){
			$col = $v1;
			$op = $v2;
			$val = $v3;
		}else if(isset($v2)){
			$col = $v1;
			$op = '=';
			$val = $v2;
		}else{

			// Ottengo automaticamente la chiave primaria
			$col = "(SELECT k.column_name
				FROM information_schema.table_constraints t
				JOIN information_schema.key_column_usage k
				USING(constraint_name,table_schema,table_name)
				WHERE t.constraint_type='PRIMARY KEY'
					AND t.constraint_schema='".DB::$config['database']."'
					AND t.table_name='{$this -> builder -> table}')

			";

			$op = '=';
			$val = $v1;
		}

		if($v4)$val = $t -> setPrepare($val);

		$r = "{$col} {$op} {$val}";

		switch($ao){
			case 'AND':
				$t -> builder -> andWhere[] = " ({$r}) ";
			break;
			case 'OR':
				$t -> builder -> orWhere[] = " ({$r}) ";
			break;
		}

		return $t;
	}
	
	/**
	 * Aggiunge una condizione WHERE IN alla query dove i risultati devono avere il valore della 
	 * colonna specificata presente nella lista di elementi
	 * @param $v (string) nome della colonna
	 * @param $a (array) array di valori accettati
	 * @return (object) clone di $this
	 */
	public function whereIn($v,$a){
		$t = clone $this;
		foreach($a as &$k)$k = $t -> setPrepare($k);
		$a = implode($a,",");
		$t -> builder -> andWhere[] = "({$v} IN ($a))";
		return $t;
	}

	/**
	 * Aggiunge una condizione OR WHERE IN alla query dove i risultati devono avere il valore della 
	 * colonna specificata presente nella lista di elementi
	 * @param $v (string) nome della colonna
	 * @param $a (array) array di valori accettati
	 * @return (object) clone di $this
	 */
	public function orWhereIn($v,$a){
		$t = clone $this;
		foreach($a as &$k)$k = $t -> setPrepare($k);
		$a = implode($a,",");
		$t -> builder -> orWhere[] = "({$v} IN ($a))";
		return $t;
	}

	/**
	 * Aggiunge una condizione WHERE LIKE alla query dove i risultati devono avere il valore della 
	 * colonna specificata presente nella lista di elementi
	 * @param $v1 (string) nome della colonna
	 * @param $v2 (string) valore ricercato
	 * @return (object) clone di $this
	 */
	public function whereLike($v1,$v2){

		$t = clone $this;
		$t -> builder -> andWhere[] = "({$v1} LIKE {$t -> setPrepare($v2)})";
		return $t;
	}

	/**
	 * Aggiunge una condizione OR WHERE LIKE alla query dove i risultati devono avere il valore della 
	 * colonna specificata presente nella lista di elementi
	 * @param $v1 (string) nome della colonna
	 * @param $v2 (string) valore ricercato
	 * @return (object) clone di $this
	 */
	public function orWhereLike($v1,$v2){

		$t = clone $this;
		$t -> builder -> orWhere[] = "({$v1} LIKE {$t -> setPrepare($v2)})";
		return $t;
	}

	/**
	 * Aggiunge una condizione WHERE IS NULL alla query dove i risultati devono avere il valore della
	 * colonna nullo
	 * @param $v (string) nome della colonna
	 * @return (object) clone di $this
	 */
	public function whereIsNull($v){
		$t = clone $this;
		$t -> builder -> andWhere[] = "({$v} IS NULL)";
		return $t;
	}
	
	/**
	 * Aggiunge una condizione WHERE IS NOT NULL alla query dove i risultati devono avere il valore della
	 * colonna non nullo
	 * @param $v (string) nome della colonna
	 * @return (object) clone di $this
	 */
	public function whereIsNotNull($v){
		$t = clone $this;
		$t -> builder -> andWhere[] = "({$v} IS NOT NULL)";
		return $t;
	}

	/**
	 * Innietta del codice sql per una condizione AND WHERE alla query
	 * @param $v (string) codice sql
	 * @return (object) clone di $this
	 */
	public function whereRaw($v){
		$t = clone $this;
		$t -> builder -> andWhere[] = "(".$t -> setPrepare($v).")";
		return $t;
	}
	
	/**
	 * Innietta del codice sql per una condizione OR WHERE alla query
	 * @param $v (string) codice sql
	 * @return (object) clone di $this
	 */
	public function orWhereRaw($v){
		$t = clone $this;
		$t -> builder -> orWhere[] = "(".$t -> setPrepare($v).")";
		return $t;
	}

	/**
	 * Ritorna il codice SQL per la condizione WHERE
	 * @param $where (bool) indica se è necessario aggiungere il comando WHERE (true, di default) o no (false)
	 * @return (string) codice SQL
	 */
	private function getWhereSQL($where = true){
		$s = $where ? ' WHERE ' : '';

		$r = array();

		if(!empty($this -> builder -> andWhere))
			$r[] = '('.implode($this -> builder -> andWhere," AND ").')';

		if(!empty($this -> builder -> orWhere))
			$r[] = '('.implode($this -> builder -> orWhere," OR ").')';

		$r = implode($r," AND ");

		return empty($r) ? "" : $s.$r;
	}

	/**
	 * Incrementa il valore della colonna
	 * @param $c (string) nome della colonna
	 * @param $v (array) valore di incremento
	 * @return (object) clone di $this
	 */
	public function increment($c,$v = 1){
		$t = clone $this;
		$t -> builder -> update[] = "{$c} = {$c} + ".$t -> setPrepare($v);
		return $t;
	}
	
	/**
	 * Decrementa il valore della colonna
	 * @param $c (string) nome della colonna
	 * @param $v (array) valore di decremento
	 * @return (object) clone di $this
	 */
	public function decrement($c,$v = 1){
		$t = clone $this;
		$t -> builder -> update[] = "{$c} = {$c} - ".$t -> setPrepare($v);
		return $t;
	}



	public function getTableOperation(){
		$r = !empty($this -> builder -> table_as) ? " AS {$this -> builder -> table_as} " : '';
		return "{$this -> builder -> table} {$r}";
	}

	/**
	 * Effettua una LEFT JOIN con un'altra tabella
	 * @param $t (string) nome della tabella secondaria
	 * @param $v1 (string) nome della colonna della tabella primaria
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto delle colonne, altrimenti il nome della colonna della tabella secondaria
	 * @param $v3 (string) optional nome della colonna della tabella secondaria
	 * @param $v4 (bool) optional indica se assegnare automaticamente le tabella alle colonne (true) o no (false)
	 * @return (object) $this
	 */
	public function leftJoin($t,$v1,$v2,$v3 = NULL,$v4 = true){
		return $this -> _join('LEFT JOIN',$t,$v1,$v2,$v3,$v4);
	}

	/**
	 * Effettua una RIGHT JOIN con un'altra tabella
	 * @param $t (string) nome della tabella secondaria
	 * @param $v1 (string) nome della colonna della tabella primaria
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto delle colonne, altrimenti il nome della colonna della tabella secondaria
	 * @param $v3 (string) optional nome della colonna della tabella secondaria
	 * @param $v4 (bool) optional indica se assegnare automaticamente le tabella alle colonne (true) o no (false)
	 * @return (object) $this
	 */
	public function rightJoin($t,$v1,$v2,$v3 = NULL,$v4 = true){
		return $this -> _join('RIGHT JOIN',$t,$v1,$v2,$v3,$v4);
	}

	/**
	 * Effettua una JOIN con un'altra tabella
	 * @param $t (string) nome della tabella secondaria
	 * @param $v1 (string) nome della colonna della tabella primaria
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto delle colonne, altrimenti il nome della colonna della tabella secondaria
	 * @param $v3 (string) optional nome della colonna della tabella secondaria
	 * @param $v4 (bool) optional indica se assegnare automaticamente le tabella alle colonne (true) o no (false)
	 * @return (object) $this
	 */
	public function join($t,$v1,$v2,$v3 = NULL,$v4 = true){
		return $this -> _join('JOIN',$t,$v1,$v2,$v3,$v4);
	}

	/**
	 * Aggiunge il codice SQL per una JOIN|LEFT JOIN|RIGHT JOIN
	 * @param $ACT (string) tipo di JOIN
	 * @param $table (string) nome della tabella secondaria
	 * @param $v1 (string) nome della colonna della tabella primaria
	 * @param $v2 (string) se $v3 è definito indica l'operatore di confronto delle colonne, altrimenti il nome della colonna della tabella secondaria
	 * @param $v3 (string) optional nome della colonna della tabella secondaria
	 * @param $v4 (bool) optional indica se assegnare automaticamente le tabella alle colonne (true) o no (false)
	 * @return (object) clone di $this
	 */
	public function _join($ACT,$table,$v1,$v2,$v3 = NULL,$v4 = true){

		$t = clone $this;

		if(isset($v3)){
			$c1 = $v1;
			$op = $v2;
			$c2 = $v3;
		}else{
			$c1 = $v1;
			$op = " = ";
			$c2 = $v2;
		}

		$t -> builder -> join[] = ($v4)
			? "{$ACT} {$table} ON {$this -> builder -> table}.{$c1} {$op} {$table}.{$c2}"
			: "{$ACT} {$table} ON {$c1} {$op} {$c2}";

		return $t;

	}

	/**
	 * Esegue la query e inserisce un record ignorando possibili duplicati
	 * @param $v (string) array di elementi da inserire (nome colonna => valore colonna)
	 * @return (object) $this
	 */
	public function insertIgnore($v){
		return $this -> insert($v,true);
	}
	
	/**
	 * Esegue la query e inserisce un record se non è presente nessun record, altrimenti aggiorna
	 * @param $v (string) array di elementi da inserire|aggiornare (nome colonna => valore colonna)
	 * @param $ignore (bool) se impostato richiama insertIgnore(true) o insert(false)
	 * @return (int) numero di risultati affetti dalla query(update) o ultimo id inserito(insert)
	 */
	public function insertUpdate($v,$ignore = false){
		return $this -> count() == 0
			? $ignore 
				? $this -> insertIgnore($v) 
				: $this -> insert($v)
			: $this -> update($v);
	}

	/**
	 * Esegue la query e inserisce un record
	 * @param $a (array) array di elementi da inserire (nome colonna => valore colonna)
	 * @param $ignore (bool) ignora i duplicati(true) o riproduce un errore(false)
	 * @return (int) ultimo id inserito
	 */
	public function insert($a,$ignore = false){

		if(empty($a))return 0;
		$t = clone $this;

		$kf = array();
		$vk = array();
		foreach($a as $k => $v){
			$kf[] = $k;
			$v = DB::quote($v);
			$vk[] = $t -> setPrepare($v);
		}

		$ignore = $ignore ? ' IGNORE ' : '';
		$t -> query("
			INSERT {$ignore} INTO {$this -> getTableOperation()} 
			(".implode($kf,",").") 
			VALUES (".implode($vk,",").") 
		");

		return DB::insert_id();

	}

	/**
	 * Esegue la query e inserisce almeno un record
	 * @param $nv (array) array costituito dai nomi delle colonne da inserire
	 * @param $av (array) array costituito da un array di valori per ogni riga
	 * @param $ignore (bool) ignora i duplicati(true) o riproduce un errore(false)
	 * @return (int) ultimo id inserito
	 */
	public function insertMultiple($nv,$av,$ignore = false){
		
		if(empty($av) || empty($nv))return 0;

		$t = clone $this;
		$vkk = array();

		if(is_closure($av)){
			$c = $av();
			$t -> builder -> prepare = array_merge($t -> builder -> prepare,$c -> builder -> prepare);
			$vkk = "(".$c -> getSelectSQL().")";

		}else{
			foreach($av as $k){
				$vk = array();
				foreach($k as $v){
					$v = DB::quote($v);
					$vk[] = $t -> setPrepare($v);
				}
				$vkk[] = "(".implode($vk,",").")";
			}
			$vkk = "VALUES ".implode($vkk,",");
		}

		$nv = "(".implode($nv,",").")";

		$ignore = $ignore ? ' IGNORE ' : '';
		
		return DB::count($t -> query("
			INSERT {$ignore} INTO {$this -> getTableOperation()} 
			$nv
			$vkk
		"));
	}

	/**
	 * Esegue la query e aggiorna i record
	 * @param $v (mixed) se $v2 è definito indica il nome della colonna da aggiornare, altrimenti l'array (nome colonna => valore colonne)
	 * @param $v2 (string) optional valore della colonna da aggiornare
	 * @return (int) numero di righe coinvolte dall'aggiornamento
	 */
	public function update($v,$v2 = NULL){

		if(empty($v))return 0;

		$t = clone $this;

		if(!is_array($v) && isset($v2)){
			$kf = array("{$this -> builder -> table}.{$v} = ".$t -> setPrepare($v2));
		}else{
			$kf = empty($t -> builder -> update) ? array() : $t -> builder -> update;
			foreach($v as $k => $v){
				$kf[] = "{$this -> builder -> table}.$k = ".$t -> setPrepare($v);
			}
		}

		$q = $t -> query("
			UPDATE {$this -> getTableOperation()} 
			".implode($t -> builder -> join," ")."
			SET
			".implode($kf,",")." 
			".$this -> getWhereSQL()."
		");

		$r = DB::count($q);

		return ($r == 0 && $q) ? 1 : $r;

	}

	/**
	 * Esegue la query e aggiorna i record
	 * @param $v (mixed) se $v2 è definito indica il nome della colonna da aggiornare, altrimenti l'array (nome colonna => valore colonne)
	 * @param $v2 (string) optional valore della colonna da aggiornare
	 * @return (int) numero di righe coinvolte dall'aggiornamento
	 */
	public function updateMultiple($v,$v2){
		if(empty($v) || empty($v2))return false;
		$t = clone $this;
		$kf = empty($t -> builder -> update) ? array() : $t -> builder -> update;
		foreach($v as $k => $v){
			$s = "{$this -> builder -> table}.$k = CASE {$v}";
			$where = array();
			foreach($v2[$k] as $n1 => $k1){
				$s .= " WHEN ".$t -> setPrepare($n1)." THEN ".$t -> setPrepare($k1)." ";
				$where[] = $t -> setPrepare($n1);
			}
			$s .= " ELSE {$k} END";
			$s .= " WHERE {$v} IN(".implode(" , ",$where).")";

			$kf[] = $s;
		}


		$q = $t -> query("
			UPDATE {$this -> getTableOperation()} 
			".implode($t -> builder -> join," ")."
			SET
			".implode($kf,",")." 
			".$this -> getWhereSQL()."
		");

		$r = DB::count($q);

		return ($r == 0 && $q) ? 1 : $r;

	}

	/**
	 * Esegue la query e elimina i record selezionati
	 * @param $v (string) optional indica il nome della tabella al quale eliminare i record (usato nelle join)
	 * @return (int) numero di righe coinvolte dall'eliminazione
	 */
	public function delete($v = ''){
		if(empty($v)) $v = $this -> getTableOperation();
		return $this -> query("
			DELETE {$v} FROM {$this -> getTableOperation()} 
			".implode($this -> builder -> join," ")."
			".$this -> getWhereSQL()."
		");
	}

	/**
	 * Esegue la query eliminazione
	 * @param $v (string) optional indica il nome della tabella al quale eliminare i record (usato nelle join)
	 * @return (int) numero di righe coinvolte dall'eliminazione
	 */
	public function truncate(){
		return $this -> query("
			TRUNCATE {$this -> builder -> table} 
		");
	}
	
	/**
	 * Raggruppa i risultati con valori di una colonna specifica uguali
	 * @param $v (string) nome della colonna coinvolta nel raggruppamento
	 * @return (object) clone di $this
	 */
	public function groupBy($v){
		$t = clone $this;
		$t -> builder -> groupBy[] = $v;
		return $t;
	}

	/**
	 * Restituisce il codice SQL da eseguire per il raggruppamento
	 * @return (string) codice SQL
	 */
	public function getGroupBySQL(){
		$s = implode($this -> builder -> groupBy," , ");
		if(!empty($s))$s = " GROUP BY {$s} ";
		return $s;
	}
	
	/**
	 * Configura la colonna che andrà ad occupare l'indice dell'array dei risultati
	 * @param $v (string) nome colonna
	 * @return (string) nome del valore
	 */
	public function setIndexArrayResult($v){
		$this -> builder -> indexArrayResult = $v;
		return $this;
	}

	/**
	 * Esegue la query e restituisce i record selezionati come risultato
	 * @return (array) risultato della query
	 */
	public function lists(){
		$r = $this -> assoc($this -> getSelectSQL());
		if(!empty($this -> builder -> indexArrayResult)){
			$s = array();
			foreach($r as $n => $k){
				$s[$k[$this -> builder -> indexArrayResult]] = $k;
			}

			$r = $s;
		}

		return $r;
	}

	/**
	 * Esegue la query e restituisce il record selezionato come risultato
	 * @return (array) risultato della query
	 */
	public function get(){
		$r = $this -> take(1) -> lists();

		return !empty($r) ? $r[0] : array();
	}

	/**
	 * Ritorna il codice SQL per la selezione
	 * @return (string) codice SQL
	 */
	public function getSelectSQL(){

		if(empty($this -> builder -> select))$this -> builder -> select[] = "*";

		$t = "";
		$i = 0;

		$c = "
			SELECT ".implode($this -> builder -> select,",")." FROM {$this -> getTableOperation()} 
			".implode($this -> builder -> join," ")."
			".$this -> getWhereSQL()."
			".$this -> getGroupBySQL()."
			".$this -> getOrderBySQL()."
			".$this -> getLimitSQL()."
		";
		$t = empty($t) ? $c : "{$t}($c) as tmp".++$i;
		

		return $c;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	/**
	 * Esegue una query di ricerca del numero delle colonne
	 * @return (int) numero delle colonne
	 */
	public function countColumns(){
		$c = DB::table('information_schema.COLUMNS');
		
		return $c -> where('TABLE_SCHEMA',DB::getName())
			-> where('TABLE_NAME',$this -> builder -> table)
			-> count();
	}

	/**
	 * Esegue una query di ricerca dell'esistenza della colonna
	 * @param (string) tipo di colonna prestabilita o codice SQL che definisce il tipo di colonna
	 * @return (object) $this
	 */
	public function hasColumn($v){
		$c = DB::table('information_schema.COLUMNS');
		return $c -> where('TABLE_SCHEMA',DB::getName())
			-> where('TABLE_NAME',$this -> builder -> table)
			-> where('COLUMN_NAME',$v)
			-> count() == 1;
	}

	/**
	 * Definisce la colonna con la quale bisogna operare
	 * @param (string) nome della colonna
	 * @return (object) $this
	 */
	public function column($v){
		$this -> schema = new stdObject();
		$this -> schema -> column = strtolower($v);
		$this -> schema -> add = array();
		$this -> schema -> foreign = new stdObject();

		return $this;
	}

	/**
	 * Ritorna il codice SQL per la selezione
	 * @param (string) tipo di colonna prestabilita o codice SQL che definisce il tipo di colonna
	 * @return (object) $this
	 */
	public function type($t){

		switch($t){
			case 'timestamp': $t = "INT(10)"; break;
			case 'varchar': $t = "VARCHAR(55)"; break;
			case 'md5': $t = "CHAR(32)"; break;
			case 'id': $t = "BIGINT(11) AUTO_INCREMENT PRIMARY KEY"; break;
			case 'big_int': $t = "BIGINT(11) "; break;
			case 'tiny_int': $t = "TINYINT(1) "; break;
			case 'text': $t = 'TEXT'; break;
			case 'float': $t = "DOUBLE"; break;
			case 'cod': $t = "VARCHAR(11)"; break;
		}

		$this -> schema -> add[] = "{$this -> schema -> column} {$t}";
		return $this;
	}

	
	/**
	 * Rende la colonna una chiave primaria
	 * @return (object) $this
	 */
	public function primary(){
		$this -> schema -> add[] = " PRIMARY KEY({$this -> schema -> column}) ";
		return $this;
	}

	/**
	 * Rende la colonna una chiave unica
	 * @return (object) $this
	 */
	public function unique(){
		$this -> schema -> add[] = " UNIQUE({$this -> schema -> column}) ";
		return $this;

	}

	/**
	 * Rende la colonna un indice
	 * @return (object) $this
	 */
	public function index(){
		$this -> schema -> add[] = " INDEX({$this -> schema -> column}) ";
		return $this;
	}

	/**
	 * Rende la colonna una chiave esterna
	 * @param $t (string) nome della tabella referenziata
	 * @param $v (string) nome della colonna referenziata
	 * @return (object) $this
	 */
	public function foreign($t,$v){
		if(!empty($this -> schema -> foreign -> column ))
			$this -> updateForeign();

		$this -> schema -> foreign -> table = $t;
		$this -> schema -> foreign -> column = $v;
		return $this;
	}

	/**
	 * Aggiunge un codice SQL quando ogni volta che avviene un'eliminazione
	 * @param $c (string) codice SQL
	 */
	public function onDelete($c){
		$this -> schema -> foreign -> onDelete = " ON DELETE {$c} ";
	}
	
	/**
	 * Aggiunge un codice SQL quando ogni volta che avviene un aggiornamento
	 * @param $c (string) codice SQL
	 */
	public function onUpdate($c){
		$this -> schema -> foreign -> onDelete = " ON UPDATE {$c} ";
	}

	/**
	 * Restituisce il codice SQL che definisce le chiavi esterne
	 */
	private function updateForeign(){
		$this -> schema -> add[] = "
			ADD FOREIGN KEY ({$this -> schema -> column}) 
			REFERENCES {$this -> schema -> foreign -> table}({$this -> schema -> foreign -> column})
			{$this -> schema -> foreign -> onDelete}
			{$this -> schema -> foreign -> onUpdate}
		";

		$this -> schema -> foreign -> column = "";
		$this -> schema -> foreign -> table = "";
		$this -> schema -> foreign -> onDelete = "";
		$this -> schema -> foreign -> onUpdate = "";
	}

	/**
	 * Esegue una query di modifica dello schema del database secondo i parametri impostati precedentemente
	 * @return (object) risultato della query
	 */
	public function alter(){
		if(!DB::$config['alter_schema']) return;

		
		if(!$this -> getCacheNameTable($this -> builder -> table)){
			$this -> addCacheNameTable($this -> builder -> table);
			if(!$this -> builder -> is_table && !DB::hasTable($this -> builder -> table)){
				$this -> builder -> is_table = true;
				$this -> query("CREATE TABLE IF NOT EXISTS {$this -> builder -> table}( ".implode($this -> schema -> add,",").")");
			}
		}

		if(!$this -> hasColumn($this -> schema -> column)){
			return $this -> query("
				ALTER TABLE {$this -> builder -> table} ADD ".implode($this -> schema -> add,", ADD")."
			");
		}

		return false;
	}

	/**
	 * Esegue una query di reset del contatore auto_increment
	 * @return (object) risultato della query
	 */
	public function resetAutoIncrement(){
		return $this -> query("ALTER TABLE {$this -> builder -> table} AUTO_INCREMENT = 1");
	}
	
	/**
	 * Aggiunge il nome della tabella alla cache interna. Questo serve ad evitare che la richiesta dell'esistenza
	 * di una tabella venga ripetuta. * FORSE DA ELIMINARE *
	 * @param (string) nome della tabella
	 */
	public function addCacheNameTable($r){
		self::$cacheAlter[] = $r;
	}

	/**
	 * Restituisce l'esistenza della tabella nella lista cache
	 * @return (bool) la tabella è già stata controllata(true) oppure no(false)
	 */
	public function getCacheNameTable($r){
		return in_array($r,self::$cacheAlter);
	}

}