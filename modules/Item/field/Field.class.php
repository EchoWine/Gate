<?php

class Field{
	
	/**
	 * Name
	 */
	public $name;

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
	public static $template = 'item.field';

	/**
	 * Is operation add enabled
	 */
	public $add = true;

	/**
	 * Is operation edit enabled
	 */
	public $edit = true;


	/**
	 * Where print the field
	 */
	public $print = [];

	/**
	 * Construct
	 * @param $n name
	 */
	public function __construct($n){
		$this -> name = $n;
		$this -> ini();
	}

	/**
	 * Initialization
	 */
	public function ini(){
		$this -> iniLabel();
		$this -> iniForm();
		$this -> iniPrint();
		$this -> iniColumn();
	}

	/**
	 * Initialize column
	 */
	public function iniColumn(){
		$this -> column = $this -> name;
	}

	/**
	 * Initialize label
	 */
	public function iniLabel(){
		$this -> label = '[undefined name]';
	}

	/**
	 * Initialize print
	 */
	public function iniPrint(){
		$this -> print = (object)[
			'list' => $this -> label,
			'get' => $this -> label,
			'form' => $this -> label,
			'value' => true,
		];
	}

	/**
	 * Get printList
	 */
	public function getPrintList(){
		return $this -> print -> list;
	}

	/**
	 * Get printGet
	 */
	public function getPrintGet(){
		return $this -> print -> get;
	}

	/**
	 * Get printForm
	 */
	public function getPrintForm(){
		return $this -> print -> form;
	}

	/**
	 * Get printValue
	 */
	public function getPrintValue(){
		return $this -> print -> value;
	}


	/**
	 * Set model
	 * @param $m (object ItemModel) model
	 */
	public function setModel($m){
		$this -> model = $m;
	}

	/**
	 * Alter the database to add column
	 */
	public function alterDatabase(){
		DB::table($this -> model -> name) -> column($this -> column) -> type('string') -> alter();
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
	 * @return (string) form name
	 */
	public function getFormName(){
		return $this -> form -> name;
	}

	/**
	 * Get form value
	 * @return (mixed) form value
	 */
	public function getFormValue(){
		return $this -> form -> value;
	}

	/**
	 * Get form value to print
	 * @param $r (array) result
	 * @return (mixed) form value
	 */
	public function printValue($r){
		return $this -> getPrintValue() ? $r[$this -> getColumnName()] : '';
	}

	/**
	 * Get path of input
	 * @return (string) path
	 */
	public function getPathInputData(){
		return self::$template.'.Field';
	}

	/**
	 * Get column name
	 * @return (string) column name
	 */
	public function getColumnName(){
		return $this -> column;
	}

	/**
	 * Add the field to the query 'add'
	 * @param $a (array) array used in the query
	 */
	public function add(&$a){
		if($this -> getAdd()){
			$a[$this -> getColumnName()] = $this -> dbValue($this -> getFormValue());
		}
	}

	/**
	 * Is operation add enabled
	 * @return (bool) result
	 */
	public function getAdd(){
		return $this -> add;
	}

	/**
	 * Add the field to the query 'edit'
	 * @param $a (array) array used in the query
	 */
	public function edit(&$a){
		if($this -> getEdit()){
			$a[$this -> getColumnName()] = $this -> dbValue($this -> getFormValue());
		}
	}

	/**
	 * Prepare value field to query
	 * @param $v (mixed) value of field
	 * @param (mixed) value prepared
	 */
	public function dbValue($v){
		return $v;
	}
	

	/**
	 * Is operation edit enabled
	 * @return (bool) result
	 */
	public function getEdit(){
		return $this -> edit;
	}



}
?>