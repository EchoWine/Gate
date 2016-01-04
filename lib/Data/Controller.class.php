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
	 * Update the path
	 * @param $v (string) name of view
	 */
	public function updatePathTemplate($v){

		# Get name of module with path
		$n = basename(dirname_r(debug_backtrace()[0]['file'],3));
		$this -> setPathTemplate($v,ModuleManager::getPathModule($n)."/bin/{$v}/templates/".TemplateEngine::getName()."/");
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

	/**
	 * Return the path to template
	 * @param $v (string) name of view
	 * @return (string) path
	 */
	public function getPathTemplate($v){
		return isset($this -> pathTemplate[$v]) ? $this -> pathTemplate[$v] : '';
	}

	/**
	 * Set template path
	 * @param $v (string) name of view
	 * @param (string) path
	 */
	public function setPathTemplate($v,$p){
		$this -> pathTemplate[$v] = $p;
	}
}
?>