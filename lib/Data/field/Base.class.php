<?php
namespace Field;

class Base{
	
	public $name;
	public $model;

	public function __construct($n){
		$this -> name = $n;
	}

	public function setModel($m){
		$this -> model = $m;
	}

	public function alterDatabase(){
		\DB::table($this -> model -> name) -> column($this -> name) -> type('string') -> alter();
	}
}
?>