<?php
class ModuleManager{
  	

	/**
	 * List of all modules
	 */
  	public static $list = array();

  	/**
  	 * Path where are located modules
  	 */
  	public static $path;

  	/**
  	 * Load all modules
  	 * @param $path (string) path where are located modules
  	 */
	public static function loadAll($path){
		self::$path = $path;

		foreach(glob($path.'/*') as $k){
			self::$list[] = basename($k);
			self::load($k);
		}
	}

	/**
	 * Load a module
	 * @param $path (string) path of module
	 */
	public static function load($path){
		include $path."/main.php";
	}

}
?>