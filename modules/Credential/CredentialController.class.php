<?php

class CredentialController extends Controller{
	public $model;
	public $cfg;

	public function __constructor($model){
		$this -> model = $model;
		$this -> checkData();
		$model -> check();
	}


}

?>