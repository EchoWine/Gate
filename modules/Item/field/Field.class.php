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
		$this -> print = new \stdClass();
		$this -> print -> list = $this -> label;
		$this -> print -> get = $this -> label;
		$this -> print -> form = $this -> label;
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
	 * Get form value
	 * @return (mixed) form value
	 */
	public function getFormValue(){
		return $this -> form -> value;
	}

}
?>