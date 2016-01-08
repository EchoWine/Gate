<?php

class Item extends Module{

	/**
	 * Config
	 */
	public static $cfg;
	
	/**
	 * Name
	 */
	public $name = null;
	
	/**
	 * Label
	 */
	public $label = null;
	
	/**
	 * Table name
	 */
	public $tableName = null;

	/**
	 * Primary field
	 */
	public $primary;

	/**
	 * Label field
	 */
	public $fieldLabel;

	/**
	 * List of all fields
	 */
	public $fields;

	/**
	 * Config
	 */
	public static $config;

	/**
	 * Field that order the result
	 */
	public $orderByField;

	/**
	 * Direction order (asc, desc)
	 */
	public $orderDirection;

	/**
	 * List of all field searched
	 */
	public $searched = [];
	
	/**
	 * Construct
	 */
	public function __construct(){
		$this -> ini();
	}
	
	/**
	 * Initialize
	 */
	public function ini(){
		$this -> iniName();
		$this -> iniLabel();
		$this -> iniTable();
	}

	/**
	 * Initialize name
	 */
	public function iniName(){
		if($this -> name === null)
			$this -> name = $this -> retrieveName();
	}

	/**
	 * Initialize name
	 */
	public function iniLabel(){
		if($this -> name === null)
			$this -> name = $this -> retrieveLabel();
	}


	/**
	 * Initialize table
	 */
	public function iniTable(){
		if($this -> tableName === null)
			$this -> tableName = $this -> retrieveTableName();
	}

	/**
	 * Retrieve name
	 */
	public function retrieveName(){
		return 'item';
	}

	/**
	 * Retrieve table name
	 */
	public function retrieveLabel(){
		return $this -> name;
	}
	/**
	 * Retrieve table name
	 */
	public function retrieveTableName(){
		return $this -> name;
	}

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
		$k -> ini();
	}

	/**
	 * Get a field
	 * @param $v (string) name field
	 * @return (object) field
	 */
	public function getField($v){
		return isset($this -> fields[$v]) ? $this -> fields[$v] : null;
	}

	/**
	 * Return if field exists
	 * @param $v (string) name field
	 * @return (object) field
	 */
	public function isField($v){
		return isset($this -> fields[$v]);
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
	public function setFieldPrimary($p){
		$this -> primary = $this -> fields[$p];
	}
	
	/**
	 * Set field label
	 * @param $p (string) name of field
	 */
	public function setFieldLabel($p){
		$this -> fieldLabel = $this -> fields[$p];
	}

	/**
	 * Select a record using the primary key
	 * @param $p (mixed) value of primary key
	 * @return (array) query result
	 */
	public function getResultByPrimary($p){
		return $this -> getQueryAlter() -> where($this -> primary -> getColumnName(),$p) -> get();
	}

	/**
	 * Get QueryBuilder select
	 */
	public function getQuerySelect(){
		return DB::table($this -> tableName);
	}

	/**
	 * Get QueryBuilder alter data
	 */
	public function getQueryAlter(){
		return DB::table($this -> tableName);
	}


	/**
	 * Select all record
	 * @param (int) $s start from
	 * @param (int) $n take n element
	 * @param (object) $oField field sort
	 * @param (string) $oDir sorting direction
	 * @param (array) $search searched
	 * @return (array) query result
	 */
	public function getResults($s = 0,$n = 5,$oField = null,$oDir = 'asc',$search = []){

		$q = $this -> getQuerySelect() -> skip($s) -> take($n);

		if($oField !== null){
			$q = $oDir == 'asc' 
				? $q -> orderByAsc($oField -> getColumnName()) 
				: $q -> orderByDesc($oField -> getColumnName());
		}

		foreach((array)$search as $n => $k){

			$q = $q -> where(function($q) use ($n,$k){
				foreach((array)$k as $k1)
					$q = $this -> getField($n) -> search($q,$k1);

				return $q;
			});
		}

		return $q -> setIndexResult($this -> primary -> getColumnName()) -> lists();
	}

	/**
	 * Select all record whre
	 * @param (int) $where where
	 * @param (int) $s start from
	 * @param (int) $n take n element
	 * @param (object) $oField field sort
	 * @param (string) $oDir sorting direction
	 * @return (array) query result
	 */
	public function getResultsWhere($where,$s = 0,$n = 5,$oField = null,$oDir = 'asc',$search = []){

		$q = $this -> getQuerySelect();

		if($oField !== null){
			$q = $oDir == 'asc' 
				? $q -> orderByAsc($oField -> getColumnName()) 
				: $q -> orderByDesc($oField -> getColumnName());
		}

		foreach((array)$where as $k)
			$q = $q -> whereIn($k[0],$k[1]); 

		$q = $q -> skip($s) -> take($n);

		return $q -> setIndexResult($this -> primary -> getColumnName()) -> lists();
	}

	/**
	 * Count all record
	 * @return (int) number of records
	 */
	public function countAll(){
		return $this -> getQuerySelect() -> count();
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

	/**
	 * Check form
	 * @param $f (array) list of all fields
	 * @param $req (bool) required
	 * @param $mul (bool) multiple
	 * @return (object stdResponse) result of request
	 */
	public function checkForm($f,$req = true,$mul = false){

		$r = [];
		foreach($f as $k){
			$v = $k -> getFormValue();
			if(!$mul || $k -> getSearch() != 2)$v = [$v];

			foreach((array)$v as $k1){

				if((!$req && !empty($k1) || $req) && $k -> getPrintForm() && !$k -> checkForm($k1)){
					$r[] = $k -> errorForm();
				}
			}
		}

		if(!empty($r))
			return new stdResponse(0,'Error form',$r);

	}


	/**
	 * Add new record
	 * @param $f (array) list of all fields
	 * @return (object stdResponse) result of request
	 */
	public function add($f){

		if(($r = $this -> checkForm($f)) !== null)return $r;

		$a = [];
		foreach($f as $k){
			$k -> add($a);
		}

		if($this -> getQueryAlter() -> insert($a)){
			return new stdResponse(1,'Success','Added');
		}

		return new stdResponse(0,'Error','Not Added');

	}

	/**
	 * Delete a record
	 * @param $f (array) list of all fields
	 * @param $p (array) value of primary key
	 * @return (object stdResponse) result of request
	 */
	public function delete($f,$p){

		$q = $this -> getQueryAlter() -> whereIn($this -> primary -> getColumnName(),$p) -> delete();

		if($q){
			return new stdResponse(1,'Success','Deleted: '.implode(",",$p));
		}

		return new stdResponse(0,'Error','Not Deleted: '.implode(",",$p));

	}

	/**
	 * Check if a record exists
	 * @param $p (array) value of primary key
	 * @return (bool) result of query
	 */
	public function exists($p){
		$q = $this -> getQuerySelect() -> exists($this -> primary -> getColumnName(),$p);
		return [array_keys($q,1),array_keys($q,0)];

	}

	/**
	 * Search
	 * @param $f (array) list of all fields
	 * @return (object stdResponse) result of request
	 */
	public function search($f){

		if(($r = $this -> checkForm($f,false,true)) !== null)return $r;
		
		$a = [];
		foreach($f as $k){
			foreach((array)$k -> getFormValue() as $k1)
				if($k1 !== '')$a[$k -> name][] = $k1;
		}

		$this -> searched = $a;

		return null;


	}

	/**
	 * Edit a record
	 * @param $f (array) list of all fields
	 * @param $p (mixed) value of primary key
	 * @param $m (array) multiple value of primary key to apply the changes
	 * @return (object stdResponse) result of request
	 */
	public function edit($f,$p,$m){

		# Remove checking of unique field if action is edit multiple
		if(!empty($m)){
			foreach($f as $n => $k)
				if($k -> unique)unset($f[$n]);
		}

		if(($r = $this -> checkForm($f)) !== null)return $r;
		
		$a = [];
		foreach($f as $k){
			$k -> edit($a);
		}

		$p = empty($m) ? [$p] : array_merge([$p],$m);

		$q = $this -> getQueryAlter() -> whereIn($this -> primary -> getColumnName(),$p) -> update($a);

		if($q){
			return new stdResponse(1,'Success','Edited');
		}


		return new stdResponse(0,'Error','Not Edited');

	}

	/**
	 * Copy a record
	 * @param $f (array) list of all fields
	 * @param $p (array) value of primary key
	 * @return (object stdResponse) result of request
	 */
	public function copy($f,$p){
		

		$c = [];

		$q = $this -> getQueryAlter() -> whereIn($this -> primary -> getColumnName(),$p) -> lists();

		foreach($q as $r){

			$a = [];

			foreach($f as $k){
				$k -> copy($a,$r);
			}

			$h[] = array_values($a);

			if(empty($c))
				$c = array_keys($a);

		}

		$q = $this -> getQueryAlter() -> insertMultiple($c,$h);

		if($q){
			return new stdResponse(1,'Success','Copied');
		}


		return new stdResponse(0,'Error','Not Copied');

	}

}

?>