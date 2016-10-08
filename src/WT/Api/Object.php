<?php

namespace WT\Api;

class Object{

	public function __set($var,$value){

		if(is_object($value) && empty((array)$value))
			return;

		$this -> {$var} = $value;
	}

	public function __get($var){

		if(!isset($this -> {$var}))
			return null;

	
	}
}