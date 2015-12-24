<?php

class ItemController extends Controller{
	public $model;
	public $cfg;

	public function __constructor($model,$cfg){
		$this -> model = $model;
		$this -> cfg = $cfg;
	}

}

?>