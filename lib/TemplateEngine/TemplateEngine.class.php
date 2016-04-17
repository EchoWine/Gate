<?php

/**
 * Time of caching:
 * -1 : Disabled
 * >= 0: Enabled
*/
define('TMPL_CACHE',-1);

class TemplateEngine{

	/**
	 * Path base to templates
	 */
	public static $basePath;

	/**
	 * Path of views
	 */
	public static $pathSource;

	/**
	 * Main path of views
	 */
	public static $pathSourceMain;

	/**
	 * Path of storage
	 */
	public static $pathStorage;

	/**
	 * Log
	 */
	public static $log;

	/**
	 * Files
	 */
	public static $files;

	/**
	 * List of all error
	 */
	public static $error = [];

	/**
	 * List of all variables alredy checked
	 */
	public static $checked = [];

	/**
	 * List of all file compiled
	 */
	public static $compiled = [];

	/**
	 * List of all blocks
	 */
	public static $blocks = [];

	/**
	 * Initialization
	 *
	 * @param string $storage path where views elaborated will be located
	 */
	public static function ini($storage){

		self::$pathStorage = $storage;
	}

	/**
	 * Parse the path
	 *
	 * @param string $path
	 * @param string path 
	 */
	public static function parsePath($path){

		if($path[0] !== "/")
			$path = "/".$path;

		return $path;
	}

	/**
	 * Get include
	 *
	 * @param string $p file name
	 * @return array array of files to be included
	 */
	public static function getInclude($p,$sub = null){

		$p = self::parsePath($p);

		$c = self::getPathSourceFile($p,$sub).".php";
	
		return $c;

	}

	/**
	 * Get all file located in a dir
	 *
	 * @param string $path path where are located all views
	 */
	public static function getAllViews($path){
		$r = [];
		foreach(glob($path."/*") as $k){
			if(is_dir($k))
				$r = array_merge($r,self::getAllViews($k));
			else
				$r[] = $k;
		}
		return $r;
	}

	/**
	 * Get the path of a view file using the $source as root
	 *
	 * @param string $source path source
	 * @param string $file path file
	 * @return string 
	 */
	public static function getPathViewBySource($source,$file){
		return str_replace($source,'',pathinfo($file)['dirname']);
	}

	/**
	 * Get path source file
	 *
	 * @param string $path path file
	 * @param string $sub path file
	 * @return string full path
	 */
	public static function getPathSourceFile($path,$sub = ''){
		return self::getPathSourceFileByFull(self::getNameSub($path,$sub));
	}

	/**
	 * Get path source file by full path
	 *
	 * @param string $path path file
	 * @return string full path
	 */
	public static function getPathSourceFileByFull($path){
		return sha1($path);
	}

	/**
	 * Get name file with sub
	 *
	 * @param string $path path file
	 * @param string $sub path file
	 * @return string full path
	 */
	public static function getNameSub($path,$sub = ''){
		return $sub !== '' ? "/".$sub."".$path : $path;
	}

	/**
	 * Compile all the page
	 *
	 * @param string $pathSource path where is located file .html to compile
	 * @param string $subPath relative path where store all files
	 */
	public static function compile($pathSource,$subPath = ''){

		self::$pathSource[$subPath] = $pathSource;

		if(empty($subPath))
			self::$pathSourceMain = $pathSource; 
		
		$pathStorage = self::$pathStorage;

		if(!file_exists(dirname($pathStorage)))
			mkdir(dirname($pathStorage), 0777, true);

		foreach(self::getAllViews($pathSource) as $k){


			/* Get dir path of file with root as $pathSource */

			$path_filename = self::getPathViewBySource($pathSource,$k);
			$filename = $path_filename."/".basename($k,".html");
			$b = self::getPathSourceFile($filename,$subPath);

			$pathStorageFile = $pathStorage."/".$b.".php";

			# Check source of file
			$t = !file_exists($pathStorageFile) || (file_exists($k) && file_exists($pathStorageFile) && filemtime($k) > filemtime($pathStorageFile));

			if(true){
				
				$content = TemplateEngine::getContentsByFileName($k);
				$content = self::translate($k,$content,$subPath,$path_filename);
				

				file_put_contents($pathStorageFile,$content);
			}

			$file = $subPath.$filename;
			if($file[0] == "/")$file = substr($file, 1);

			self::$files[$pathSource][] = $file;
		}

		if(!empty(self::$error)){
			self::printErrors(self::$error);
			die();
		}
	}

	/**
	 * Translate the page
	 *
	 * @param string $filename file name
	 * @param string $ccontent content of the page
	 * @param string $subPath name of "class of files"
	 */
	private static function translate($filename,$content,$subPath = '',$relativePath = ''){

		$translator = new Translator($filename,$subPath,$relativePath);
		return $translator -> translate($content);

	}

	/**
	 * Get source of file base on absolute filename
	 * 
	 * @param string $filename
	 * @return string
	 */
	public static function getContentsByFilename($filename){
		return file_get_contents($filename);
	}

	/**
	 * Get source of file based on relative filename
	 * 
	 * @param string $filename
	 * @return string
	 */
	public static function getSourceFile($filename){


		foreach(TemplateEngine::$files as $path => $files){
			foreach($files as $file){
				if($file == $filename){
					return TemplateEngine::getContentsByFilename($path."/".$file.".html");
				}
			}
		}

		die('No file found');

	}
	
	/**
	 * Print error
	 *
	 * @param array $e list of all error
	 */
	public static function printErrors($e){
	
		echo 	"<div style='border: 1px solid black;margin: 0 auto;padding: 20px;'>
					<h1>Template engine - Errors</h1>";

		foreach($e as $k)
			echo $k -> file."(".$k -> row."): ".$k -> message."<br>";
		
		echo 	"</div>";
		
	}

	/**
	 * Main function that print the page
	 *
	 * @param string $page name page
	 * @param string $sub sub
	 * @return string page
	 */
	public static function html($page,$sub = ''){
		return self::$pathStorage."/".self::getInclude($page,$sub);
	}

}

?>