<?php

class Field{
	
	public $name;

	public $model;

	public static $template = 'item.field';

	public function __construct($n){
		$this -> name = $n;

		$this -> ini();
	}

	public function ini(){
		$this -> iniLabel();
		$this -> iniPrint();
		$this -> iniColumn();
	}

	public function iniColumn(){
		$this -> column = $this -> name;
	}

	public function iniLabel(){
		$this -> label = '[undefined name]';
	}

	public function iniPrint(){
		$this -> print = new \stdClass();
		$this -> print -> list = $this -> label;
		$this -> print -> get = $this -> label;
		$this -> print -> form = $this -> label;
	}

	public function setModel($m){
		$this -> model = $m;
	}

	public function alterDatabase(){
		DB::table($this -> model -> name) -> column($this -> column) -> type('string') -> alter();
	}

	public function getInput(){
		return "<input name='{$this -> name}'>";
	}

	public function getInputData(){
		return self::$template.'.Field';
	}
}
?>