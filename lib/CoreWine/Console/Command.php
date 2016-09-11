<?php

namespace CoreWine\Console;

class Command{

	public static $signature;

	public $parameters;

	public function __construct($argv){
		$this -> parameters = $argv;
	}


}

?>