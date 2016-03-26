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
	 */
	public function __construct(){
		$this -> ini();
	}

	/**
	 * Initialize
	 */
	public function ini(){}
	
	/**
	 * Update the data
	 */
	public function updateData(){
		$this -> data = $this -> retrieveData();
	}

	/**
	 * Retrieve all data sent by user
	 *
	 * @return array data
	 */
	public function retrieveData(){
		return [];
	}

	/**
	 * Get all information about a data
	 *
	 * @param string $v name of data
	 * @return object data
	 */
	public function getData($v){
		return isset($this -> data[$v]) ? $this -> data[$v] : 'null';
	}

	/**
	 * Get name (used in form) data
	 *
	 * @param string $v  name data
	 * @return string name
	 */
	public function getDataName($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> name : 'null';
	}
	
	/**
	 * Get value (get by form) data
	 *
	 * @param string $v name data
	 * @return mixed value
	 */
	public function getDataValue($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> value : 'null';
	}

	/**
	 * Get label (used in form) data
	 *
	 * @param string $v name data
	 * @return string label
	 */
	public function getDataLabel($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> label : 'null';
	}

	/**
	 * Get option of data
	 *
	 * @param string $v name data
	 * @param string $o name option
	 * @return string option
	 */
	public function getDataOption($v,$o){
		return isset($this -> data[$v]) && isset($this -> data[$v] -> option[$o])
			? $this -> data[$v] -> option[$o] 
			: 'null';
	}

	/**
	 * Get all option of data
	 *
	 * @param string $v name data
	 * @return string option
	 */
	public function getDataAllOption($v){
		return isset($this -> data[$v]) ? $this -> data[$v] -> option : 'null';
	}

	/**
	 * Return the path to template
	 *
	 * @param string $v name of view
	 * @return string path
	 */
	public function getPathTemplate($v){
		return isset($this -> pathTemplate[$v]) ? $this -> pathTemplate[$v] : '';
	}

	/**
	 * Set template path
	 *
	 * @param string $v name of view
	 * @param string path
	 */
	public function setPathTemplate($v,$p){
		$this -> pathTemplate[$v] = $p;
	}
}
?>