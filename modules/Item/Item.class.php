<?php

class Item extends Module{

	/**
	 * Config
	 */
	public static $cfg;

	/**
	 * Path app
	 */
	public static $pathApp;
	
	/**
	 * Return file inc
	 */
	public static function getPathApp(){
		return self::$pathApp;
	}

}

?>