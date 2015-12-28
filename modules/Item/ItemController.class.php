<?php

class ItemController extends Controller{

	public $namePage;
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

			# Page
			'page' => new stdDataGet('page',self::$cfg['post_page']),


		];
	}

	public function getResults(){
		return $this -> model -> getResults();
	}

	public function setNamePage($p){
		$this -> namePage = $p;
	}

	public function getUrlMainPage(){
		return '?'.PAGE.'='.$this -> namePage;
	}


	public function getPageValue(){
		return $this -> getData('page') -> value;
	}

	public function getPageParamAdd(){
		return self::$cfg['page']['add'];
	}

	public function ini(){
		$this -> iniButton();
		$this -> iniFieldsList();
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
		return $this -> getUrlMainPage().'&amp;'.self::$cfg['post_page'].'='.self::$cfg['page']['add'];
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