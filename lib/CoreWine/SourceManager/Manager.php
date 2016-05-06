<?php

namespace CoreWine\SourceManager;

use CoreWine\Cfg;
use CoreWine\TemplateEngine;

class Manager{
  	

	/**
	 * List of all src
	 */
  	public static $list = [];

	/**
	 * List of all files loaded
	 */
  	public static $files = [];

  	/**
  	 * Path where are located src
  	 */
  	public static $path;

  	/**
  	 * List of controllers
  	 */
  	public static $controllers;

  	/**
  	 * Basic path
  	 */
  	public static $src;

  	/**
  	 * Set path where are located src
  	 * @param string $path path
  	 */
  	public static function setPath($path){
  		self::$path = $path;
  	}

	/**
  	 * Get path where are located src
  	 * @return $path (string) path
  	 */
  	public static function getPath(){
  		return self::$path;
  	}

  	/**
  	 * Load all src
  	 * @param string $path path where are located src
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

		if(!empty(self::$list[$basePath]))
			return;

		self::$list[$basePath] = $path;

		foreach(glob($path."/Controller/*") as $file){

			self::$files[] = $file;
			require_once $file;

			$name_class = str_replace(PATH_SRC,"",$file);
			$name_class = str_replace("/","\\",$name_class);
			$name_class = str_replace(".php","",$name_class);

			self::$controllers[] = $name_class;
		}

		# Create symlink to access 
		if(!file_exists(PATH."/src/".$basePath)){
			if(file_exists(PATH."/../src/".$basePath."/Resources/public/")){
				symlink(PATH."/../src/".$basePath."/Resources/public/",PATH."/src/".$basePath);
			}
		}

		# Set config
		foreach(glob($path.'/Resources/config/*') as $file){
			$cfgs = require_once $file;
			$base = basename($file,".php");
			foreach($cfgs as $cfg_name => $cfg_value){
				Cfg::set($base.".".$cfg_name,$cfg_value);
			}
		}
	

	}

	/**
  	 * Get path of a module
  	 * @param string $n name of module
  	 * @return $path (string) path
  	 */
  	public static function getPathModule($m){
  		return isset(self::$list[$m]) ? self::$list[$m] : null;
  	}


	public static function loaded(){

		$controllers = [];
		foreach(self::$controllers as $controller){
			
			if(is_subclass_of($controller,Controller::class)){
				$reflectionClass = new \ReflectionClass($controller);


	    		if($reflectionClass -> IsInstantiable()){
					$c = new $controller();	

					
					$c -> __routes();

					$controllers[] = $c;
				}
			}
		}

		foreach($controllers as $controller){
			$controller -> __check();
		}

	}
	

}
?>