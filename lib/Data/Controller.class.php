<?php
class Controller{
  	
	public $model;

	public function __construct($model){
		$this -> model = $model;
	}

	public function check(){
		$this -> checkData();
		$this -> model -> check();
	}

	public function checkData(){
		$this -> data = array(
			'action' => 'add'
		);
	}


}
?>