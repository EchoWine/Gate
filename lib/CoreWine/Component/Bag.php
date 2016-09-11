<?php

namespace CoreWine\Component;

class Bag{

	public $resource = [];

	public function __construct($ini){
		$this -> resource = $ini;
	}

	public function set($name,$value){
		$this -> resource[$name] = $value;
	}

	public function get($name,$default = null){
		return isset($this -> resource[$name]) ? $this -> resource[$name] : $default;
	}

	public function all(){
		return $this -> resource;
	}
}

?>