<?php
class ModuleManager{
  	

	/**
	 * List of all modules
	 */
  	public static $list = [];

	/**
	 * List of all files loaded
	 */
  	public static $files = [];

  	/**
  	 * Path where are located modules
  	 */
  	public static $path;

  	/**
  	 * List of controllers
  	 */
  	public static $controllers;

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

		$folders  = ['Controller','Model'];

		if(!empty(self::$list[$basePath]))
			return;

		self::$list[$basePath] = $path;

		foreach($folders as $folder){
			foreach(glob($path."/".$folder.'/*') as $file){
				self::$files[] = $file;
				require $file;

				$name_class = str_replace(PATH_MODULE,"",$file);
				$name_class = str_replace("/","\\",$name_class);
				$name_class = str_replace(".php","",$name_class);

				if($folder == 'Controller')
					self::$controllers[] = $name_class;
			}
		}

	}

	/**
	 * Call all template methods in module
	 *
	 */
	public static function loadTemplate(){
		foreach(self::$list as $name => $dir)
			TemplateEngine::compile($dir."/Resources/views",$name);
		
	}

	/**
  	 * Get path of a module
  	 * @param string $n name of module
  	 * @return $path (string) path
  	 */
  	public static function getPathModule($m){
  		return isset(self::$list[$m]) ? self::$list[$m] : null;
  	}

  	public static function loadClass($class){
  		$file = PATH_MODULE.'/'.__NAMESPACE__.$class.".php";

  		if(in_array($file,self::$files))
			require $file;
	}

	public static function loaded(){
		foreach(self::$controllers as $controller)
			new $controller();

	}
	

}

spl_autoload_register(__NAMESPACE__ . "\\ModuleManager::loadClass");
?>