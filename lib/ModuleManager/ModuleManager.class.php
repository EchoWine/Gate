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
  	 * Set path where are located modules
  	 * @param string $path path
  	 */
  	public static function setPath($path){
  		self::$path = $path;
  	}

	/**
  	 * Get path where are located modules
  	 * @return $path (string) path
  	 */
  	public static function getPath(){
  		return self::$path;
  	}

  	/**
  	 * Load all modules
  	 * @param string $path path where are located modules
  	 */
	public static function loadAll($path){
		self::$path = $path;

		foreach(glob($path.'/*') as $k){
			self::load($k);
		}
	}

	/**
	 * Load a module
	 *
	 * @param string $path path of module
	 */
	public static function load($path){
		$basePath = basename($path);

		if(empty(self::$list[$basePath])){
			self::$list[$basePath] = $path;
			include $path."/main.php";
		}
	}

	/**
	 * Call all template methods in module
	 *
	 * @param string $v name of view
	 */
	public static function loadTemplate($v){
		$r = array();
		foreach(self::$list as $n => $k){
			$r[] = (object)[
				'name' => $n,
				'app' => $k."/bin/{$v}/app.php",
			];
		}
		return $r;
	}

	/**
  	 * Get path of a module
  	 * @param string $n name of module
  	 * @return $path (string) path
  	 */
  	public static function getPathModule($m){
  		return isset(self::$list[$m]) ? self::$list[$m] : null;
  	}

}
?>