<?php
class Model{
  	
  	public $name;
  	public $primary;
  	public $fields;	

  	public function __construct($n){
  		$this -> name = $n;
  	}

	public function setFields(array $a){
		foreach($a as $k)
			$this -> setField($k);
		
	}

	public function setField($k){
		$this -> fields[$k -> name] = $k;
		$k -> setModel($this);
	}

	public function check(){
		$this -> alterDatabase();
	}

	public function alterDatabase(){

		foreach($this -> fields as $k){
			$k -> alterDatabase();
		}

	}

	public function setPrimary($p){
		$this -> primary = $this -> fields[$p];
	}

	public function getByPrimary($p){

		return DB::table($this -> name) -> where($this -> primary,$p) -> get();

	}

	public function getAll(){

		return DB::table($this -> name) -> lists();

	}

	public function getFieldsNameInList(){
		$r = [];
		foreach($this -> fields as $k){
			if($k -> print -> list) $r[] = $k -> print -> list;
		}
		return $r;
	}

	public function getFieldsNameInGet(){
		$r = [];
		foreach($this -> fields as $k){
			if($k -> print -> get) $r[] = $k -> print -> get;
		}
		return $r;
	}


	public function add(){
		
	}
}
?>