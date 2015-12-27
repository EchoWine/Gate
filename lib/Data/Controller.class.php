<?php
class Controller{
  	
	/**
	 * Model
	 */
	public $model;
	
	/**
	 * Data
	 */
	public $data;

	/**
	 * Construct
	 * @param $model (object) model
	 */
	public function __construct($model){
		$this -> model = $model;
	}

	/**
	 * Check all the interaction with user
	 */
	public function check(){
		$this -> model -> alterTable();
		$this -> updateData();
	}

	/**
	 * Update the data
	 */
	public function updateData(){
		$this -> data = $this -> retrieveData();
	}

	/**
	 * Retrieve all data sent by user
	 * @return (array) data
	 */
	public function retrieveData(){
		return [];
	}

	/**
	 * Get all information about a data
	 * @param (string) $v name of data
	 * @return (object) data
	 */
	public function getData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] : 'null';
	}

	/**
	 * Get name (used in form) of data
	 * @param (string) $v name of data
	 * @return (string) name
	 */
	public function getNameData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> name : 'null';
	}
	
	/**
	 * Get value (get by form) of data
	 * @param (string) $v name of data
	 * @return (mixed) value
	 */
	public function getValueData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> value : 'null';
	}

	/**
	 * Get label (used in form) of data
	 * @param (string) $v name of data
	 * @return (string) label
	 */
	public function getLabelData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> label : 'null';
	}

}
?>