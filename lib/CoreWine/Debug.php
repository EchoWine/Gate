<?php

namespace CoreWine;

class Debug{

	public static $data;

	public static function add($data){
		Debug::$data[] = $data;
	}

	public static function print(){
		print_r(Debug::$data);
	}
}

?>