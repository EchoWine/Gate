<?php

class ItemController extends Controller{

	public $nameURL;
	public $button;
	public static $cfg;


	/**
	 * Check all the interaction with user
	 */
	public function check(){
		$this -> updateData();
	}

	/**
	 * Retrieve all data sent by user
	 * @return (array) data
	 */
	public function retrieveData(){
		return [

			# Action
			'action' => new stdDataGet('action',Item::$cfg['get_action'],null),

			# Page
			'page' => new stdDataGet('page',Item::$cfg['get_page'],1),

		];
	}

	public function getResults(){

		# Initialization
		$r = new stdClass();

		# Set count
		$r -> count = $this -> model -> countAll();

		# Set list
		$this -> setList($r -> count);

		# Get records
		$r -> records = $this -> model -> getResults($this -> getResultStartFrom(),$this -> getResultPerPage());

		return $r;
	}

	public function checkPage($r){
		if($this -> getData('page') -> value > $r)
			$this -> getData('page') -> value = $r;


		if($this -> getData('page') -> value < 1)
			$this -> getData('page') -> value = 1;

		
	}

	public function setList($r){


		$this -> list -> pagTotal = $this -> getTotalPages($r);

		$this -> checkPage($this -> list -> pagTotal);

		$this -> list -> pagValue = $this -> getData('page') -> value;


		$this -> list -> pagValuePrev = $this -> list -> pagValue == 1 
			? 1 
			: $this -> list -> pagValue - 1;

		$this -> list -> pagValueNext = $this -> list -> pagValue == $this -> list -> pagTotal 
			? $this -> list -> pagTotal
			: $this -> list -> pagValue + 1;
	}

	public function getResultStartFrom(){
		$r = $this -> getResultPerPage();
		return $this -> getData('page') -> value * $r - $r;
	}

	public function getResultPerPage(){
		return Item::$cfg['result_page'];
	}

	public function getTotalPages($c){
		return ceil($c / $this -> getResultPerPage());
	}

	public function setNameURL($p){
		$this -> nameURL = $p;
	}

	public function getUrlMainPage(){
		return '?'.PAGE.'='.$this -> nameURL;
	}


	public function getPageActionValue(){
		return $this -> getData('action') -> value;
	}

	public function getPageParamAdd(){
		return Item::$cfg['action']['add'];
	}

	public function ini(){
		$this -> iniButton();
		$this -> iniFieldsList();
	}

	public function iniList(){
		$this -> list = new stdClass();

		$this -> iniPagination();
	}

	public function iniPagination(){
		$this -> list -> pagName = Item::$cfg['get_page'];
	}

	public function getPagTotal(){

	}

	public function iniButton(){
		$this -> button = new stdClass();
		$this -> iniToAdd();
		$this -> iniToList();
	}

	public function iniToAdd(){
		$this -> button -> toAdd = (object)[
			'url' => $this -> getUrlPageAdd(),
		];
	}

	public function iniToList(){
		$this -> button -> toList = (object)[
			'url' => $this -> getUrlPageList(),
		];
	}

	public function getUrlPageAdd(){
		return $this -> getUrlMainPage().'&amp;'.Item::$cfg['get_action'].'='.Item::$cfg['action']['add'];
	}

	public function getUrlPageList(){
		return $this -> getUrlMainPage();
	}

	public function iniFieldsList(){
		$this -> fieldsList = $this -> getFieldsList();
	}

	public function getFieldsList(){
		$r = [];
		foreach($this -> model -> fields as $k){
			$r[] = $k;
		}

		return $r;
	}
}

?>