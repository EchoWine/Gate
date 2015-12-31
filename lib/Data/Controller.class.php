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
	 * Get name (used in form) data
	 * @param $v (string)  name data
	 * @return (string) name
	 */
	public function getDataName($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> name : 'null';
	}
	
	/**
	 * Get value (get by form) data
	 * @param $v (string) name data
	 * @return (mixed) value
	 */
	public function getDataValue($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> value : 'null';
	}

	/**
	 * Get label (used in form) data
	 * @param $v (string) name data
	 * @return (string) label
	 */
	public function getDataLabel($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> label : 'null';
	}

	/**
	 * Get option of data
	 * @param $v (string) name data
	 * @param $o (string) name option
	 * @return (string) option
	 */
	public function getDataOption($v,$o){
		return isset($this -> data[$v]) ? $this -> data[$v] -> option[$o] : 'null';
	}
}
?>