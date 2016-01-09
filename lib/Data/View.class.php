<?php
class View{

	/**
	 * Construct
	 *
	 * @param object $model model
	 * @param object $controller controller
	 */
	public function __construct($model,$controller){
		$this -> model = $model;
		$this -> controller = $controller;
		$this -> ini();
	}

	/** 
	 * Initialization
	 */
	public function ini(){

	}

}
?>