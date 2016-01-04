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
	 * Response
	 */
	public $response = [];

	/**
	 * Results
	 */
	public $results = [];

	/**
	 * Check all the interaction with user
	 */
	public function check(){
		$this -> updateData();
		

		$this -> checkAttemptSearch();
		$this -> checkAttemptOrder();
		$this -> checkAttemptAdd();
		$this -> checkAttemptDelete();
		$this -> checkAttemptEdit();
		$this -> checkAttemptCopy();
	}

	/**
	 * Check attempt search
	 */
	public function checkAttemptSearch(){
		if($this -> getData('action') -> value == $this -> getActionSearch()){

			if(($r = $this -> model -> search($this -> model -> fields)) !== null)
				$this -> response[] = $r;

		}
	}

	/**
	 * Check attempt sort
	 */
	public function checkAttemptOrder(){

		$v = $this -> getData('g_order') -> value;

		if($v !== null && preg_match("/^(.*)_(d|a)$/",$v,$r)){

			if($this -> model -> isField($r[1])){
				$f = $this -> model -> getField($r[1]);
				$s = $r[2] == 'd' ? 'desc' : 'asc';

				$this -> model -> orderByField = $f;
				$this -> model -> orderDirection = $s;
			}

		}
	}

	/**
	 * Check attempt add new data
	 */
	public function checkAttemptAdd(){

		if($this -> getData('action') -> value == $this -> getActionAdd()){

			if(($r = $this -> model -> search($this -> model -> fields)) !== null)
				$this -> response[] = $r;
			
		}
	}

	/**
	 * Check attempt edit data
	 */
	public function checkAttemptEdit(){

		if($this -> getData('action') -> value == $this -> getActionEdit()){

			$p = $this -> getData('p_primary') -> value;
			if($this -> checkExists($p)){
				$this -> response[] = $this -> model -> edit($this -> model -> fields,$p);
			}

		}
	}

	/**
	 * Check attempt delete
	 */
	public function checkAttemptDelete(){

		$r = $this -> checkAttemptSM($this -> getActionDeleteS(),$this -> getActionDeleteM());
		
		if(!empty($r))
			$this -> response[] = $this -> model -> delete($this -> model -> fields,$r);
	}

	/**
	 * Check attempt copy
	 */
	public function checkAttemptCopy(){
		
		$r = $this -> checkAttemptSM($this -> getActionCopyS(),$this -> getActionCopyM());

		if(!empty($r))
			$this -> response[] = $this -> model -> copy($this -> model -> fields,$r);
	}



	/**
	 * Check attempt single/multiple operation
	 * @param $s (string) value action single
	 * @param $m (string) value action multiple
	 * @return (array) primary key
	 */
	public function checkAttemptSM($s,$m){
		$a = $this -> getData('action') -> value;
		$p = null;

		if($a == $m)
			$p = $this -> getData('p_primary_m') -> value;

		if($a == $s)
			$p = [$this -> getData('p_primary') -> value];
		

		$r = [];
		if($p !== null){

			foreach($p as $k){
				if($this -> checkExists($k))$r[] = $k;
			}

		}
		return $r;
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
			'action' => new stdDataPost(Item::$cfg['post_action'],null,null,[
				'add' => 'add',
				'edit' => 'edit',
				'delete_s' => 'del_s',
				'delete_m' => 'del_m',
				'copy_s' => 'copy_s',
				'copy_m' => 'copy_m',
				'search' => 'search',
			]),

			# Page
			'page' => new stdDataGet(Item::$cfg['get_page'],1,null,[
				'prev' => 0,
				'actual' => 0,
				'next' => 0,
			]),

			# Post primary
			'p_primary' => new stdDataPost(Item::$cfg['p_primary']),

			# Post primary multiple
			'p_primary_m' => new stdDataPost(Item::$cfg['p_primary_m']),

			# Get primary
			'g_primary' => new stdDataGet(Item::$cfg['g_primary']),

			# Get order
			'g_order' => new stdDataGet(Item::$cfg['g_order']),

		];
	}

	/**
	 * Initialize
	 */
	public function ini(){

	}

	/**
	 * Get the value of action add
	 * @return (string) action add
	 */
	public function getActionAdd(){
		return Item::$cfg['action']['add'];
	}

	/**
	 * Get the value of action search
	 * @return (string) action search
	 */
	public function getActionSearch(){
		return Item::$cfg['action']['search'];
	}

	/**
	 * Get the value of action delete_s
	 * @return (string) action delete_s
	 */
	public function getActionDeleteS(){
		return Item::$cfg['action']['delete_s'];
	}

	/**
	 * Get the value of action delete_m
	 * @return (string) action delete_m
	 */
	public function getActionDeleteM(){
		return Item::$cfg['action']['delete_m'];
	}

	/**
	 * Get the value of action edit
	 * @return (string) action edit
	 */
	public function getActionEdit(){
		return Item::$cfg['action']['edit'];
	}

	/**
	 * Get the value of action copy_s
	 * @return (string) action copy_s
	 */
	public function getActionCopyS(){
		return Item::$cfg['action']['copy_s'];
	}

	/**
	 * Get the value of action copy_m
	 * @return (string) action copy_m
	 */
	public function getActionCopyM(){
		return Item::$cfg['action']['copy_m'];
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

		# Set pages
		$r -> pages = $this -> getTotalPages($r -> count);
		$this -> iniPages($r -> pages);

		# Get records
		$r -> records = $this -> model -> getResults(
			$this -> getResultStartFrom(),
			$this -> getResultPerPage(),
			$this -> model -> orderByField,
			$this -> model -> orderDirection,
			$this -> model -> searched
		);

		$this -> results = $r;

		return $r;
	}

	/**
	 * Get result by primary
	 * @return (object) results
	 */
	public function getResultByPrimary(){

		# Initialization
		$r = new stdClass();
		$r -> record = [];

		$v = $this -> getData('g_primary') -> value;

		if($v === null) return $r;

		# Check if exists
		if($this -> checkExists($v)){

			# Get records
			$r -> record = $this -> model -> getResultByPrimary($v);
		}

		$this -> results = $r;

		return $r;
	}

	/**
	 * Check if record exists
	 * @param $p (mixed) primary key value
	 */
	public function checkExists($p){
		if(!$this -> model -> exists($p)){

			if(empty($this -> response['error_exists']))
				$this -> response['error_exists'] = new stdResponse(0,'Error');
			
			$r = $this -> response['error_exists'];
			$r -> addMessage("[{$p}] Not exists");

			return false;
		}

		return true;
		
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
	 * Set all information about pages
	 * @param $t (int) number of pages
	 */
	public function iniPages($t){

		$v = $this -> getData('page') -> value;


		$this -> getData('page') -> option['actual'] = $v;

		$this -> getData('page') -> option['prev'] = $v == 1 
			? 1 
			: $v - 1;

		$this -> getData('page') -> option['next'] = $v == $t 
			? $t
			: $v + 1;

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
	 * Get the value of action page view
	 * @return (string) action page view
	 */
	public function getPageActionView(){
		return Item::$cfg['action']['view'];
	}


	/**
	 * Get the url to the add action page
	 * @param $p (mixed) optional primary key value
	 * @return (string) url
	 */
	public function getUrlPageAdd($p = null){
		$r = $this -> getUrlMainPage().
		'&amp;'.Item::$cfg['get_action'].'='.$this -> getPageActionAdd();

		if($p !== null)
			$r .= '&amp;'.Item::$cfg['g_primary'].'='.$p;

		return $r;
	}

	/**
	 * Get the url to the edit action page
	 * @param $p (mixed) primary key value
	 * @return (string) url
	 */
	public function getUrlPageEdit($p = ''){
		return $this -> getUrlMainPage().
		'&amp;'.Item::$cfg['get_action'].'='.$this -> getPageActionEdit().
		'&amp;'.Item::$cfg['g_primary'].'='.$p;
	}

	/**
	 * Get the url to the view action page
	 * @param $p (mixed) primary key value
	 * @return (string) url
	 */
	public function getUrlPageView($p = ''){
		return $this -> getUrlMainPage().
		'&amp;'.Item::$cfg['get_action'].'='.$this -> getPageActionView().
		'&amp;'.Item::$cfg['g_primary'].'='.$p;
	}
	
	
	/**
	 * Get the url to the list action page
	 * @param $oField (string) order by field 
	 * @return (string) url
	 */
	public function getUrlPageList($oField = null){
		$r = $this -> getUrlMainPage();

		if($oField !== null && $this -> model -> isField($oField))
			$r .= '&amp;'.Item::$cfg['g_order'].'='.$this -> getParamOrder($this -> model -> getField($oField));

		return $r;
	}

	/**
	 * Get param order
	 * @param $f (string) name field
	 * @return (string) param
	 */
	public function getParamOrder($f){
		return $this -> model -> orderByField == $f && $this -> model -> orderDirection !== 'desc' 
			? $f -> name."_d" 
			: $f -> name."_a";
	}

	/**
	 * Get status order of field
	 * @param $f (string) name field
	 * @return (string) param
	 */
	public function getOrderField($f){
		return $this -> model -> orderByField -> name == $f ? $this -> model -> orderDirection : '';
	}

	/**
	 * Get all fields
	 * @return (array) array of fields
	 */
	public function getFieldsList(){
		$r = [];
		foreach($this -> model -> fields as $k){
			if($k -> getPrintList() !== null)
				$r[] = $k;
		}

		return $r;
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


	/**
	 * Get all fields 
	 * @return (array) array of fields
	 */
	public function getFieldsView(){
		$r = [];
		foreach($this -> model -> fields as $k){
			if($k -> getView())
				$r[] = $k;
		}

		return $r;
	}

	/**
	 * Get all fields 
	 * @return (array) array of fields
	 */
	public function getFieldsSearch(){
		$r = [];
		foreach($this -> model -> fields as $k){
			if($k -> getPrintList() !== null && $k -> getSearch() != 0)
				$r[] = $k;
		}

		return $r;
	}

	/**
	 * Get all fields 
	 * @param $n (string) name of field
	 * @return (array) array of searched word
	 */
	public function getSearched($n){
		return isset($this -> model -> searched[$n]) ? $this -> model -> searched[$n] : [];
	}

	/**
	 * Return the primary field
	 * @return (object) primary field
	 */
	public function getFieldPrimary(){
		return $this -> model -> primary;
	}
}

?>