<?php
class Controller{
  	
	public $model;

	public function __construct($model){
		$this -> model = $model;
	}

	public function check(){
		$this -> model -> check();
	}


}
?>