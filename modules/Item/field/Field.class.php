<?php

class Field{
	
	/**
	 * Name
	 */
	public $name;

	/**
	 * Column
	 */
	public $column = null;

	/**
	 * Label
	 */
	public $label = null;

	/**
	 * Model
	 */
	public $model;

	/**
	 * Information about form
	 */
	public $form;

	/**
	 * Name of template page
	 */
	public static $template = 'field';

	/**
	 * Is operation add enabled
	 */
	public $add = true;

	/**
	 * Is operation edit enabled
	 */
	public $edit = true;

	/**
	 * Is operation copy enabled
	 */
	public $copy = true;

	/**
	 * Is field unique
	 */
	public $unique = false;

	/**
	 * Search type {0: Disabled, 1: Single, 2: Multiple}
	 */
	public $search = 2;

	/**
	 * Print the value in the input
	 */
	public $printInputValue = true;

	/**
	 * Print the value sent by user if checkForm return false
	 */
	public $printInputValueData = true;

	/**
	 * Basic pattern
	 */
	public $_pattern = "(.)";

	/**
	 * Pattern complete
	 */
	public $pattern;

	/**
	 * Min length value
	 */
	public $minLength = 0;

	/**
	 * Max length value
	 */
	public $maxLength = 128;

	/**
	 * Where print the field
	 */
	public $print = [];

	/**
	 * Construct
	 *
	 * @param mixed $n name or attribute
	 */
	public function __construct($n){
		if(is_array($n)){
			foreach($n as $n => $k)
				$this -> {$n} = $k;
		}else{
			$this -> name = $n;
		}
	}

	/**
	 * Initialization
	 */
	public function ini(){
		$this -> _ini();
		$this -> iniLabel();
		$this -> iniForm();
		$this -> iniPrint();
		$this -> iniColumn();
		$this -> iniPattern();
	}

	/**
	 * Extension of initialization
	 */
	public function _ini(){}

	/**
	 * Initialize column
	 */
	public function iniColumn(){
		if($this -> column == null)
			$this -> column = $this -> name;
	}

	/**
	 * Initialize label
	 */
	public function iniLabel(){
		if($this -> label == null)
			$this -> label = $this -> name;
	}

	/**
	 * Initialize pattern
	 */
	public function iniPattern(){
		$this -> pattern = "/^".$this -> _pattern."{".$this -> minLength.",".$this -> maxLength."}$/iU";;
	}

	/**
	 * Initialize print
	 */
	public function iniPrint(){
		$this -> print = (object)[
			'list' => $this -> label,
			'view' => $this -> label,
			'form' => $this -> label,
		];
	}

	/**
	 * Get print List
	 */
	public function getPrintList(){
		return $this -> print -> list;
	}

	/**
	 * Get print View
	 */
	public function getPrintView(){
		return $this -> print -> view;
	}

	/**
	 * Get print Form
	 */
	public function getPrintForm(){
		return $this -> print -> form;
	}

	/**
	 * Set model
	 *
	 * @param object ItemModel $m model
	 */
	public function setModel($m){
		$this -> model = $m;
	}

	/**
	 * Alter the database to add column
	 */
	public function alterDatabase(){
		DB::schema($this -> model -> name) -> string($this -> column,$this -> maxLength) -> alter();
	}

	/**
	 * Initialize form
	 */
	public function iniForm(){
		$this -> form = new stdClass();
		$this -> iniFormName();
		$this -> iniFormValue();
	}

	/**
	 * Initialize form name
	 */
	public function iniFormName(){
		$this -> form -> name = $this -> name;
	}

	/**
	 * Initialize form value
	 */
	public function iniFormValue(){
		$this -> form -> value = isset($_POST[$this -> getFormName()]) ? $_POST[$this -> getFormName()] : null;
	}

	/**
	 * Get form name
	 *
	 * @return string form name
	 */
	public function getFormName(){
		return $this -> form -> name;
	}

	/**
	 * Get form name search
	 *
	 * @return string form name
	 */
	public function getFormNameSearch(){
		return $this -> getFormName()."[]";
	}

	/**
	 * Get form value
	 *
	 * @return mixed form value
	 */
	public function getFormValue(){
		return $this -> form -> value;
	}

	/**
	 * Get form value to print
	 *
	 * @param array $r result
	 * @return mixed form value
	 */
	public function printInputValue($r){
		
		if($this -> printInputValue){
			if($this -> printInputValueData && $this -> getFormValue() !== null)
				return $this -> getFormValue();

			if(!empty($r) && isset($r[$this -> getColumnName()]))
				return $r[$this -> getColumnName()];
		}

		return '';

	}

	/**
	 * Get form value to print in search
	 *
	 * @param mixed $v value
	 * @return mixed form value
	 */
	public function printInputValueSearch($v){
		return $this -> printInputValue ? $v : '';
	}

	/**
	 * Get value to print
	 *
	 * @param array $r result
	 * @return mixed value
	 */
	public function printValue($r){
		return $r[$this -> getColumnName()];
	}

	/**
	 * Get path of input
	 *
	 * @return string path
	 */
	public function getPathInputData(){
		return self::$template.'/Field';
	}

	/**
	 * Check if value is valid
	 *
	 * @param mixed $v value to validate
	 * @return bool is value valid
	 */
	public function checkForm($v){
		return preg_match($this -> pattern,$v);
	}

	/**
	 * Return the error message 
	 *
	 * @return string error message
	 */
	public function errorForm(){
		return "Field <b>{$this -> label}</b> not valid";
	}

	/**
	 * Get column name
	 *
	 * @return string column name
	 */
	public function getColumnName(){
		return $this -> column;
	}

	/**
	 * Add the field to the query 'add'
	 *
	 * @param array $a array used in the query
	 */
	public function add(&$a){
		if($this -> getAdd()){
			$a[$this -> getColumnName()] = $this -> dbValue($this -> getFormValue());
		}
	}

	/**
	 * Add the field to the query 'edit'
	 *
	 * @param array $a array used in the query
	 */
	public function edit(&$a){
		if($this -> getEdit()){
			$a[$this -> getColumnName()] = $this -> dbValue($this -> getFormValue());
		}
	}

	/**
	 * Add the field to the query 'copy'
	 *
	 * @param array $a array used in the query
	 * @param array $r result from select
	 */
	public function copy(&$a,$r){
		if($this -> getCopy()){
			$b = $r[$this -> getColumnName()];

			if($this -> unique)
				$b = $this -> checkUnique($b);

			$a[$this -> getColumnName()] = $b;
		}
	}

	/**
	 * Add the field to the query 'search'
	 *
	 * @param object $q query builder
	 * @param mixed $v value searched
	 * @return object query builder
	 */
	public function search($q,$v){
		return $q -> orWhereLike($this -> getColumnName(),'%'.$v.'%');
	}

	/**
	 * Find a unique value for field
	 *
	 * @param string $b base value
	 * @return string string unique
	 */
	public function checkUnique($b){
		$i = 0;

		do{
			$n = $this -> getPatternCopy($b,$i++);
		}while(
			DB::table($this -> model -> name)
			-> where($this -> getColumnName(),$n) 
			-> count() > 0
		);

		return $n;
	}

	/**
	 * Get string to use in search for unique value
	 *
	 * @param string $b base value
	 * @param int $i counter
	 * @return string result
	 */
	public function getPatternCopy($b,$i){
		return $b."".$i;
	}

	/**
	 * Prepare value field to query
	 *
	 * @param mixed $v value of field
	 * @param mixed value prepared
	 */
	public function dbValue($v){
		return $v;
	}

	/**
	 * Is operation add enabled
	 *
	 * @return bool result
	 */
	public function getAdd(){
		return $this -> getPrintForm() && $this -> add;
	}

	/**
	 * Is operation edit enabled
	 *
	 * @return bool result
	 */
	public function getEdit(){
		return $this -> getPrintForm() && $this -> edit;
	}

	/**
	 * Is operation copy enabled
	 *
	 * @return bool result
	 */
	public function getCopy(){
		return $this -> copy;
	}

	/**
	 * Type of search operation
	 *
	 * @return bool result
	 */
	public function getSearch(){
		return $this -> search;
	}


	/**
	 * Is operation view enabled
	 *
	 * @return bool result
	 */
	public function getView(){
		return $this -> getPrintView();
	}


}
?>