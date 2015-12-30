<?php

/*
	Notation:

	Page: Page of result (e.g. 1,2,3,4 etc.)
	Action Page: The current interface of action (e.g. add,edit,list etc. )
*/
class ItemController extends Controller{

	/**
	 * Name of obj in url
	 */
	public $nameURL;

	/**
	 * All information about button
	 */
	public $button;

	/**
	 * Response
	 */
	public $response = [];


	/**
	 * Check all the interaction with user
	 */
	public function check(){
		$this -> updateData();
		
		$this -> checkAttemptAdd();
	}

	/**
	 * Check attempt add new data
	 */
	public function checkAttemptAdd(){

		if($this -> getData('action') -> value == $this -> getActionAdd()){

			$this -> response[] = $this -> model -> add($this -> model -> fields);

		}
	}

	/**
	 * Retrieve all data sent by user
	 * @return (array) data
	 */
	public function retrieveData(){
		return [

			# Page action
			'page_action' => new stdDataGet('action',Item::$cfg['get_action'],null),

			# Action
			'action' => new stdDataPost('action',Item::$cfg['post_action'],null),

			# Page
			'page' => new stdDataGet('page',Item::$cfg['get_page'],1),

		];
	}

	/**
	 * Initialize
	 */
	public function ini(){
		$this -> iniButton();
		$this -> iniFieldsList();
		$this -> iniFieldsAdd();
	}

	/**
	 * Initialize list
	 */
	public function iniList(){
		$this -> list = new stdClass();

		$this -> iniPagination();
	}

	/**
	 * Initialize pagination
	 */
	public function iniPagination(){
		$this -> list -> pagName = Item::$cfg['get_page'];
	}

	/**
	 * Initialize Button
	 */
	public function iniButton(){
		$this -> button = new stdClass();
		$this -> iniAdd();
		$this -> iniToAdd();
		$this -> iniToList();
	}

	/**
	 * Initialize button toAdd
	 */
	public function iniToAdd(){
		$this -> button -> toAdd = (object)[
			'url' => $this -> getUrlPageAdd(),
		];
	}

	/**
	 * Initialize button action
	 */
	public function iniAdd(){
		$this -> button -> action = (object)[
			'name' => Item::$cfg['post_action'],
			'valueAdd' => $this -> getActionAdd(),
		];
	}

	/**
	 * Get the value of action add
	 * @return (string) action add
	 */
	public function getActionAdd(){
		return Item::$cfg['action']['add'];
	}


	/**
	 * Initialize button toList
	 */
	public function iniToList(){
		$this -> button -> toList = (object)[
			'url' => $this -> getUrlPageList(),
		];
	}

	/**
	 * Get all result
	 * @return (object) results
	 */
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

	/**
	 * Check if the data 'page' is correct
	 * @param $r (int) max page
	 */
	public function checkPage($r){
		if($this -> getData('page') -> value > $r)
			$this -> getData('page') -> value = $r;


		if($this -> getData('page') -> value < 1)
			$this -> getData('page') -> value = 1;

		
	}

	/**
	 * Set all information about list
	 * @param $r (int) number of all results
	 */
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

	/**
	 * Get the result that will be used as "start"
	 * @return (int) number of result
	 */
	public function getResultStartFrom(){
		$r = $this -> getResultPerPage();
		return $this -> getData('page') -> value * $r - $r;
	}

	/**
	 * Get the result per page
	 * @return (int) result per page
	 */
	public function getResultPerPage(){
		return Item::$cfg['result_page'];
	}

	/**
	 * Get the number of all pages
	 * @param (int) count of all records
	 * @return (int) number of all pages
	 */
	public function getTotalPages($c){
		return ceil($c / $this -> getResultPerPage());
	}

	/**
	 * Set the name url
	 * @param $p (string) name url
	 */
	public function setNameURL($p){
		$this -> nameURL = $p;
	}

	/**
	 * Get the url to the main action page
	 * @return (string) url
	 */
	public function getUrlMainPage(){
		return '?'.PAGE.'='.$this -> nameURL;
	}

	/**
	 * Get the value of the current action page
	 * @return (string) action page
	 */
	public function getPageActionValue(){
		return $this -> getData('page_action') -> value;
	}

	/**
	 * Get the value of action page add
	 * @return (string) action page add
	 */
	public function getPageActionAdd(){
		return Item::$cfg['action']['add'];
	}

	/**
	 * Get the url to the add action page
	 * @return (string) url
	 */
	public function getUrlPageAdd(){
		return $this -> getUrlMainPage().'&amp;'.Item::$cfg['get_action'].'='.Item::$cfg['action']['add'];
	}
	
	/**
	 * Get the url to the list action page
	 * @return (string) url
	 */
	public function getUrlPageList(){
		return $this -> getUrlMainPage();
	}

	/**
	 * Initialize the field list
	 */
	public function iniFieldsList(){
		$this -> fieldsList = $this -> getFieldsList();
	}

	/**
	 * Get all fields
	 * @return (array) array of fields
	 */
	public function getFieldsList(){
		$r = [];
		foreach($this -> model -> fields as $k){
			$r[] = $k;
		}

		return $r;
	}

	/**
	 * Initialize the field add
	 */
	public function iniFieldsAdd(){
		$this -> fieldsAdd = $this -> getFieldsAdd();
	}

	/**
	 * Get all fields 
	 * @return (array) array of fields
	 */
	public function getFieldsAdd(){
		$r = [];
		foreach($this -> model -> fields as $k){
			$r[] = $k;
		}

		return $r;
	}
}

?>