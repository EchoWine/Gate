<?php

class ItemController extends Controller{

	public $namePage;
	public $button;
	public static $cfg;

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
		$this -> button = new stdClass();
		$this -> setToAdd();
		$this -> setToList();
	}

	public function setToAdd(){
		$this -> button -> toAdd = (object)[
			'url' => $this -> getUrlPageAdd(),
		];
	}

	public function setToList(){
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
}

?>