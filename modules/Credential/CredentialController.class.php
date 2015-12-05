<?php

class CredentialController extends Controller{
	public $model;
	public $cfg;

	public function __constructor($model){
		$this -> model = $model;
		$this -> checkData();
		$model -> check();
	}

	public function checkData(){
		$this -> model -> data = array(

			'user' => isset($_POST[$this -> cfg['user']]) 
				? $_POST[$this -> cfg['user']] : NULL,

			'pass' => isset($_POST[$this -> cfg['pass']]) 
				? $_POST[$this -> cfg['pass']] : NULL
				
		);
	}

}

?>