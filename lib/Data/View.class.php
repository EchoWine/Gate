<?php
class View{

	/**
	 * Construct
	 * @param $model (object) model
	 * @param $controller (object) controller
	 */
	public function __construct($model,$controller){
		$this -> model = $model;
		$this -> controller = $controller;
	}

}
?>