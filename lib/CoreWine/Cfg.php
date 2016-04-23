<?php

namespace CoreWine;

class Cfg{

	private static $cfg = [];

	public static function set($index,$cfg){
		Cfg::$cfg[$index] = $cfg;
	}

	public static function get($index){

		return isset(Cfg::$cfg[$index]) ? Cfg::$cfg[$index] : null;
	}

}

?>