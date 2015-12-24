<?php

class ItemController extends Controller{
	public $model;
	public $cfg;
	public $namePage;

	public function __constructor($model,$cfg){
		$this -> model = $model;
		$this -> cfg = $cfg;
	}

	public function setNamePage($p){
		$this -> namePage = $p;
	}

	public function getUrlMainPage(){
		return '?'.PAGE.'='.$this -> namePage;
	}
}

?>