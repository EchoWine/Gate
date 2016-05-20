<?php


namespace CoreWine\TemplateEngine;

define('TMPL_CACHE',-1);

class Engine{

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
	 * List of all blocks current nested
	 */
	public static $blocks_actual = [];

	/**
	 * List of all extends nested
	 */
	public static $extends = [];

	/**
	 * List of all extends nested
	 */
	public static $extends_index = -1;

	const STRUCTURE_EXTENDS = 'EXTENDS';

	const STRUCTURE_BLOCK = 'BLOCK';

	public static $structure = null;

	public static $structure_parent = null;

	public static $structure_print = true;

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
	public static function getInclude($filename,$sub = null){

		foreach(Engine::$files as $path => $files){
			foreach($files as $file){
				if($file -> file == $filename || $file -> path."/".$file -> file == $filename){
					return $file -> storage.".php";
				}
			}
		}

		throw new \Exception("The file '$filename' doesn't exists");
		die();
	

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
	public static function getPathSourceFile($abs){
		return sha1($abs);
	}

	/**
	 * Compile all the page
	 *
	 * @param string $pathSource path where is located file .html to compile
	 * @param string $subPath relative path where store all files
	 */
	public static function compile($path,$pathSource,$subPath = ''){

		$pathSource = $path."/".$pathSource;
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

			$b = self::getPathSourceFile($k);

			$pathStorageFile = $pathStorage."/".$b.".php";


			if($filename[0] == "/")$filename = substr($filename, 1);
			$file = $subPath."/".$filename;

			self::$files[$pathSource][] = (object)[
				'abs_file' => $k,
				'file' => $file,
				'filename' => $filename,
				'sub' => $subPath,
				'storage' => $b,
				'pathStorageFile' => $pathStorageFile,
				'path_filename' => $path_filename,
				'path' => $path
			];
		}

		if(!empty(self::$error)){
			self::printErrors(self::$error);
			die();
		}
	}

	/**
	 * Translate all pages
	 */
	public static function translates(){

		foreach(self::$files as $path){
			foreach($path as $file){

				# Check source of file
				$t = !file_exists($file -> pathStorageFile) || (file_exists($file -> abs_file) && file_exists($file -> pathStorageFile) && filemtime($file -> abs_file) > filemtime($file -> pathStorageFile));

				if(true){
					
					$content = Engine::getContentsByFileName($file -> abs_file);
					$content = self::translate($file -> abs_file,$content,$file -> sub,$file -> path_filename);
					
					file_put_contents($file -> pathStorageFile,$content);
				}
			}
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

		$filename = self::getFullPathFile($filename);

		if($filename !== null)
			return Engine::getContentsByFilename($filename.".html");


	}


	/**
	 * Get source of file based on relative filename
	 * 
	 * @param string $filename
	 * @return string
	 */
	public static function getFullPathFile($filename){

		foreach(Engine::$files as $path => $files){
			foreach($files as $file){
				if($file -> file == $filename || $path."/".$file -> path == $filename){
					return $path."/".$file -> filename;
				}
			}
		}

		throw new \Exception("'$filename' not found");

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

	public static function startStructure($name,$type){


   		//ob_start();
   		return Engine::addStructure($name,$type);


	}

	public static function endStructure($type){

  		//$content = ob_get_contents();
   		//ob_end_clean();

		$content = '';

		print_r(Engine::getStructure());

   		Engine::getStructure() -> setContent($content);

   		/*
		if(Engine::getStructure() -> getParent() !== null)
			Engine::getStructure() -> getParent() -> concatContent($content);
		*/


		Engine::setParentStructure($type);


   		return $content;

	}



	public static function addStructure($name,$type){

		$structure = new Structure($name,$type);

		if(Engine::$structure_parent != null){
			$structure -> setParent(Engine::$structure_parent);
			Engine::$structure_parent -> addChild($structure);
		}

		Engine::$structure = $structure;
		

		return $structure;
	}


	public static function setParentStructure($type){

		Engine::$structure = Engine::$structure -> getParent();
	}

	public static function getStructure(){
		return Engine::$structure;
	}

	/**
	 * Start extends
	 *
	 * Must contain only blocks inside, no space/between
	 */
	public static function startExtends($name,$print = false){

		Engine::$structure_print = $print;
		$structure = Engine::startStructure($name,Engine::STRUCTURE_EXTENDS);
		Engine::$structure_parent = $structure;
	}
	/**
	 * Start extends content
	 *
	 * Must contain only blocks inside, no space/between
	 */
	public static function startExtendsContent($name){

		Engine::$structure_print = true;

	}

	/**
	 * End extends
	 */
	public static function endExtends($include = true){

		$structure = Engine::getStructure();
		$c = Engine::endStructure(Engine::STRUCTURE_EXTENDS);


		Engine::$structure_print = true;

		if($include){
			echo "INCLUDOO: ";
			echo $structure -> getName()."\n\n";
			include '/'.PATH_STORAGE.'/'.Engine::getInclude($structure -> getName());
		}

		Engine::$structure_print = false;


		Engine::$structure_parent = Engine::getStructure();

		echo $c;
		return $c;
	}

	/**
	 * Start a block
	 *
	 * @param string $name
	 */
	public static function startBlock($name){

		Engine::startStructure($name,Engine::STRUCTURE_BLOCK);
	}

	/**
	 * End last block
	 */
	public static function endBlock(){
		$c = Engine::endStructure(Engine::STRUCTURE_BLOCK);
		if(Engine::$structure_print)
			echo $c;
		return $c;

		//$content = preg_replace('/{% parent %}/',$content,Engine::$blocks[$index]);
   		


	}


}

?>