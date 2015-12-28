<?php
class Model{
	
	/**
	 * Name of model
	 */
	public $name;
	
	/**
	 * Construct
	 * @param $n (string) name of model
	 */
	public function __construct($n){
		$this -> name = $n;
	}

}
?>