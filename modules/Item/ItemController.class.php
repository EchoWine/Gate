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
	 * Primary
	 */
	public $primary;


	/**
	 * Check all the interaction with user
	 */
	public function check(){
		$this -> updateData();
		$this -> iniButton();
		
		$this -> checkAttemptAdd();
		$this -> checkAttemptDelete();
		$this -> checkAttemptEdit();
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
	 * Check attempt edit data
	 */
	public function checkAttemptEdit(){

		if($this -> getData('action') -> value == $this -> getActionEdit()){

			$this -> response[] = $this -> model -> edit($this -> model -> fields,$this -> getData('post_primary') -> value);

		}
	}

	/**
	 * Check attempt delete
	 */
	public function checkAttemptDelete(){

		if($this -> getData('action') -> value == $this -> getActionDelete()){

			$this -> response[] = $this -> model -> delete($this -> model -> fields,$this -> getData('post_primary') -> value);

		}
	}

	/**
	 * Retrieve all data sent by user
	 * @return (array) data
	 */
	public function retrieveData(){
		return [

			# Page action
			'page_action' => new stdDataGet(Item::$cfg['get_action']),

			# Action
			'action' => new stdDataPost(Item::$cfg['post_action']),

			# Page
			'page' => new stdDataGet(Item::$cfg['get_page'],1),

			# Post primary
			'post_primary' => new stdDataPost(Item::$cfg['post_primary']),

			# get primary
			'get_primary' => new stdDataGet(Item::$cfg['get_primary']),

		];
	}

	/**
	 * Initialize
	 */
	public function ini(){
		$this -> iniPrimary();
		$this -> iniFieldsList();
		$this -> iniFieldsAdd();
		$this -> iniFieldsEdit();
	}

	/**
	 * Initialize primary
	 */
	public function iniPrimary(){
		$this -> primary = $this -> model -> primary;
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
		$this -> iniButtonAdd();
		$this -> iniButtonToEdit();
		$this -> iniButtonToAdd();
		$this -> iniButtonToList();
		$this -> iniButtonPrimary();
	}

	/**
	 * Initialize button toAdd
	 */
	public function iniButtonToAdd(){
		$this -> button -> toAdd = (object)[
			'url' => $this -> getUrlPageAdd(),
		];
	}

	/**
	 * Initialize button toEdit
	 */
	public function iniButtonToEdit(){
		$this -> button -> toEdit = (object)[
			'url' => $this -> getUrlPageEdit(),
		];
	}

	/**
	 * Initialize button primary
	 */
	public function iniButtonPrimary(){
		$this -> button -> primary = $this -> getData('post_primary');
	}

	/**
	 * Initialize button action
	 */
	public function iniButtonAdd(){
		$this -> button -> action = (object)[
			'name' => $this -> getData('action') -> name,
			'value' => $this -> getData('action') -> value,
			'valueAdd' => $this -> getActionAdd(),
			'valueDelete' => $this -> getActionDelete(),
			'valueEdit' => $this -> getActionEdit(),
		];
	}

	/**
	 * Initialize button toList
	 */
	public function iniButtonToList(){
		$this -> button -> toList = (object)[
			'url' => $this -> getUrlPageList(),
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
	 * Get the value of action delete
	 * @return (string) action delete
	 */
	public function getActionDelete(){
		return Item::$cfg['action']['delete'];
	}

	/**
	 * Get the value of action edit
	 * @return (string) action edit
	 */
	public function getActionEdit(){
		return Item::$cfg['action']['edit'];
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
	 * Get result by primary
	 * @return (object) results
	 */
	public function getResultByPrimary(){

		# Initialization
		$r = new stdClass();

		# Get records
		$r -> record = $this -> model -> getResultByPrimary($this -> getData('get_primary') -> value);

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
	 * Get the value of action page edit
	 * @return (string) action page edit
	 */
	public function getPageActionEdit(){
		return Item::$cfg['action']['edit'];
	}

	/**
	 * Get the url to the add action page
	 * @return (string) url
	 */
	public function getUrlPageAdd(){
		return $this -> getUrlMainPage().'&amp;'.Item::$cfg['get_action'].'='.$this -> getPageActionAdd();
	}

	/**
	 * Get the url to the edit action page
	 * @param $p (mixed) primary key value
	 * @return (string) url
	 */
	public function getUrlPageEdit($p = ''){
		return $this -> getUrlMainPage().
		'&amp;'.Item::$cfg['get_action'].'='.$this -> getPageActionEdit().
		'&amp;'.Item::$cfg['get_primary'].'='.$p;
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
			if($k -> getAdd())
				$r[] = $k;
		}

		return $r;
	}

	/**
	 * Initialize the field edit
	 */
	public function iniFieldsEdit(){
		$this -> fieldsEdit = $this -> getFieldsAdd();
	}

	/**
	 * Get all fields 
	 * @return (array) array of fields
	 */
	public function getFieldsEdit(){
		$r = [];
		foreach($this -> model -> fields as $k){
			if($k -> getEdit())
				$r[] = $k;
		}

		return $r;
	}
}

?>