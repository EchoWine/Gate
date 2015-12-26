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
  	 * @param $path (string) path
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
  	 * @param $path (string) path where are located modules
  	 */
	public static function loadAll($path){
		self::$path = $path;

		foreach(glob($path.'/*') as $k){
			self::load($k);
		}
	}

	/**
	 * Load a module
	 * @param $path (string) path of module
	 */
	public static function load($path){
		$basePath = basename($path);
		if(empty(self::$list[$basePath])){
			self::$list[$basePath] = $path;
			include $path."/main.php";

			# Call load method
			forward_static_call(basename($path)."::load",$path);
		}
	}

	/**
	 * Call all template methods in module
	 * @param $v (string) name of view
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
}
?>