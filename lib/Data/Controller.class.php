<?php
class Controller{
  	
	public $model;

	public function __construct($model){
		$this -> model = $model;
	}

	public function check(){
		$this -> model -> data = $this -> setData();
		$this -> model -> check();
	}

	public function setData(){
		return [];
	}

	public function getData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] : 'null';
	}

	public function getNameData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> name : 'null';
	}
	
	public function getValueData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> value : 'null';
	}

	public function getLabelData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> label : 'null';
	}


}
?>