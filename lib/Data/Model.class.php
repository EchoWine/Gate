<?php
class Model{
  	
  	public $name;
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
}
?>