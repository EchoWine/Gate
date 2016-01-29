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
	 * Path of storage
	 */
	public static $pathStorage;

	/**
	 * Log
	 */
	public static $log;

	/**
	 * List of all error
	 */
	public static $error = [];

	/**
	 * List of all variables alredy checked
	 */
	public static $checked = [];

	/**
	 * List of all included files
	 */
	public static $include;

	/**
	 * List of all joined files
	 */
	public static $join;

	/**
	 * List of all file compiled
	 */
	public static $compiled = [];

	/**
	 * Initialization
	 *
	 * @param string $storage path where views elaborated will be located
	 */
	public static function ini($storage){

		self::$pathStorage = $storage;
	}

	/**
	 * Set include
	 *
	 * @param string $p name
	 * @param string $f path complete
	 */
	public static function setInclude($p,$f,$sub = ''){
		self::$include[self::parsePath($p)] = self::getPathSourceFile(self::parsePath($f),$sub).".php";
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
	 * Join a page to another
	 *
	 * @param string $nt name of page that will be joined
	 * @param string $nf name page that will joined
	 * @param int $pos position of aggregation
	 */
	public static function addJoin($nt,$nf,$sub = '',$pos = null){

		$nf = self::getPathSourceFile(self::parsePath($nf),$sub);
		$nt = self::parsePath($nt);

		if($pos == null || isset(self::$join[$nt][$pos]))
			self::$join[$nt][] = $nf;
		else
			self::$join[$nt][$pos] = $nf;
	}

	/**
	 * Get include
	 *
	 * @param string $p file name
	 * @return array array of files to be included
	 */
	public static function getInclude($p){


		$p = self::parsePath($p);

		$c[] = isset(self::$include[$p]) ? self::$include[$p] : self::getPathSourceFileByFull($p).".php";


		if(!empty(self::$join[$p])){
			$t = self::$join[$p];
			ksort($t);
			foreach((array)$t as $n => $k)
				$c[] = $k.".php";
		}

	
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

		$pathStorage = self::$pathStorage;

		if(!file_exists(dirname($pathStorage)))
			mkdir(dirname($pathStorage), 0777, true);

		foreach(self::getAllViews($pathSource) as $k){


			/* Get dir path of file with root as $pathSource */


			$p = self::getPathViewBySource($pathSource,$k);


			$b = self::getPathSourceFile($p."/".basename($k,".html"),$subPath);

			$pathStorageFile = $pathStorage."/".$b.".php";

			//self::$include[] = $b;

			# Check source of file
			$t = !file_exists($pathStorageFile) || (file_exists($k) && file_exists($pathStorageFile) && filemtime($k) > filemtime($pathStorageFile));

			if(true){
				
				$content = file_get_contents($k);
				$content = self::preCompile($k,$content,$subPath,$p);
				$content = self::translate($k,$content);
				

				file_put_contents($pathStorageFile,$content);
			}
		}

		if(!empty(self::$error)){
			self::printErrors(self::$error);
			die();
		}
	}

	/**
	 * Precompile the page
	 *
	 * @param string $f file name
	 * @param string $c content of the page
	 * @param string $subPath name of "class of files"
	 */
	private static function preCompile($f,$c,$subPath = '',$relativePath = ''){


		# Contains fun/var

			#$c = preg_replace('/{{_include ([^\}]*)}}/iU','{{include ".$1."}}',$c);
			#print_r($c);



		# In folder

		if(!empty($relativePath)){

			preg_match_all('/{{include ([^\}]*)}}/iU',$c,$r);

			foreach($r[1] as $n => $k){

				if($relativePath[0] == '/')
					$relativePath = substr($relativePath,1);
				
				$fc = '';

				if($k[0] == '.'){
					$k = substr($k,1);
					$fc = '.';
				}

				if($k[0] != '/'){

					$c = str_replace($r[0][$n],"{{include $fc"."$relativePath/".$k."}}",$c);

				}else{

					$k = substr($k,1);

					$c = str_replace($r[0][$n],"{{include $fc"."".$k."}}",$c);

				}

			}

		}

		# Variable scope include
		preg_match_all('/{{include ([^\}]*)}}/iU',$c,$r);
		foreach($r[1] as $n => $k){

			$k = preg_replace("/[\t\n\r]/iU","",$k);
			preg_match_all('/^(.*) \{(.*)$/iU',$k,$r1);
			if(!empty($r1[2][0])){
				$t = $r1[2][0];
				$t = "<?php ".str_replace(",",";",$r1[2][0])."; ?>";
				$c = str_replace($r[0][$n],$t."{{include ".$r1[1][0]."}",$c);
			}
		}


		if(!empty($subPath)){

			# Include sub Class
			preg_match_all('/{{include \.([^\}]*)}}/iU',$c,$r);
			foreach($r[0] as $n => $k){
				$c = str_replace($k,"{{include ".self::getNameSub("/".$r[1][$n],$subPath)."}}",$c);
				
			}
		}
		

		# Include
		preg_match_all('/{{include ([^\}]*)}}/iU',$c,$r);
		foreach($r[1] as $n => $k){

			/*
			if(empty(self::$include[$k]))
				TemplateEngine::setInclude($k,$k);
			*/
			

			
			$c = str_replace($r[0][$n],'<?php foreach(TemplateEngine::getInclude("'.$k.'") as $k) include $k; ?>',$c);

		}


		# Switch
		# Remove space between switch and first case
		$c = preg_replace('/{{switch ([^\}]*)}}([^\{]*){{case/iU',"{{switch $1}}\n{{case",$c);
		$c = preg_replace('/{{\/(case)}}([^\{]*){{(case)/iU','{{/case}}'."\n".'{{case',$c);
		$c = preg_replace('/{{\/(case)}}([^\{]*){{\/switch}}/iU','{{/case}}'."\n".'{{/switch}}',$c);
		return $c;
	}


	/**
	 * Translate the page
	 *
	 * @param string $f file name
	 * @param string $c content of the page
	 * @param string content translated
	 */
	private static function translate($f,$c){


		# include
		preg_match_all('/{{include ([^\}]*)}}/iU',$c,$r);
		
		foreach($r[0] as $n => $k){

			$c = str_replace($k,'<?php include '.$r[1][$n].'; ?>',$c);
		}

		# array
		preg_match_all('/{{([^\}]*)}}/iU',$c,$r);
		foreach($r[0] as $n => $k){
			$i = preg_replace('/\.([\w]*)/','[\'$1\']',$k);
			$c = str_replace($k,$i,$c);
		}

		# for 
		preg_match_all('/{{for ([^\}]*) as ([^\}]*)}}/iU',$c,$r);
		
		foreach($r[0] as $n => $k){
			self::$checked[] = $r[2][$n];

			$c = str_replace("{$k}",'<?php foreach((array)'.$r[1][$n].' as '.$r[2][$n].'){ ?>',$c);
		}

		# switch
		preg_match_all('/{{switch ([^\}]*)}}/iU',$c,$r);
	
		foreach($r[0] as $n => $k){
			$c = str_replace($k,'<?php switch('.$r[1][$n].'){ ?>',$c);
		}

		$c = preg_replace('/{{case default}}/iU','<?php default: ?>',$c);
		preg_match_all('/{{case ([^\} ]*)}}/iU',$c,$r);
	
		foreach($r[0] as $n => $k)
			$c = str_replace($k,'<?php case '.$r[1][$n].': ?>',$c);
		
		# if
		preg_match_all('/{{if ([^\}]*)}}/iU',$c,$r);
	
		foreach($r[0] as $n => $k){
			$c = str_replace($k,'<?php if('.$r[1][$n].'){ ?>',$c);
		}
		
		# else if
		preg_match_all('/{{elseif ([^\} ]*)}}/iU',$c,$r);
	
		foreach($r[0] as $n => $k)
			$c = str_replace($k,'<?php }else if('.$r[1][$n].'){ ?>',$c);


		$a = array(
			'/{{endfor}}/iU',
			'/{{endif}}/iU',
			'/{{else}}/iU',
			'/{{\/switch}}/iU',
			'/{{\/}}/iU',
			'/{{\/case}}/iU',

		);
		$r = array(
			'<?php } ?>',
			'<?php } ?>',
			'<?php }else{ ?>',
			'<?php } ?>',
			'<?php } ?>',
			'<?php break; ?>',
		);

		$c = preg_replace($a,$r,$c);
		# variables
		preg_match_all('/{{([^\}]*)}}/iU',$c,$r);
		foreach($r[1] as $n => $k){

			# Count row
			preg_match_all('/\n/',explode($k,$c)[0],$r);
			$r = count($r[0])+1;

			$v = preg_replace('/\.([\w]*)/','',$k);

			# Check if defined
			/*if(!in_array($v,self::$checked) && !isset($GLOBALS[$v])){
				$e = new stdClass();
				$e -> message = "Undefined variable {$v}";
				$e -> row = $r;
				$e -> file = basename($f);
				self::$error[] = $e;
			}*/

			$c = str_replace('{{'.$k.'}}','<?php echo '.$k.'; ?>',$c);
		}

		return $c;

	}

	/**
	 * Print error
	 *
	 * @param array $e list of all error
	 */
	public static function printErrors($e){
	
		echo 	"<div style='border: 1px solid black;margin: 0 auto;padding: 20px;'>
					<h1>Template engine - Errors</h1>";


		foreach($e as $k){
			echo 
				$k -> file."(".$k -> row."): ".$k -> message."<br>";
		}

		echo 	"</div>";
		
	}

	/**
	 * Main function that print the page
	 *
	 * @param string $page name page
	 * @return string page
	 */
	public static function html($page){
		return self::$pathStorage."/".self::getInclude($page)[0];
	}

}

?>