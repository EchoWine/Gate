<?php

/**
 * Time of caching:
 * -1 : Disabled
 * >= 0: Enabled
*/
define('TMPL_CACHE',-1);

class TemplateEngine{

	/**
	 * List of all files loaded
	 */
	public static $files;

	/**
	 * Path base to templates
	 */
	public static $basePath;

	/**
	 * List of all templates found
	 */
	public static $list;

	/**
	 * Name of the current template loaded
	 */
	public static $name;

	/**
	 * Path of the current template loaded
	 */
	public static $path;

	/**
	 * Log
	 */
	public static $log;

	/**
	 * Name dir of compiled files 
	 */
	public static $dirCompiled = 'app';

	/**
	 * List of all error
	 */
	public static $error = array();

	/**
	 * List of all variables alredy checked
	 */
	public static $checked = array();

	/**
	 * List of all overwrited files
	 */
	public static $overwrite;

	/**
	 * List of all included files
	 */
	public static $include;

	/**
	 * List of all aggregated files
	 */
	public static $aggregate;

	/**
	 * List of all file compiled
	 */
	public static $compiled = array();

	/**
	 * Initialization
	 * @param $b (string) directory name where templates is installed
	 */
	public static function ini($b){

		$t = dirname(debug_backtrace()[0]['file']);

		self::$basePath = $t."/".$b;

		foreach(glob(self::$basePath.'/*') as $k){
			self::$list[basename($k)] = $k;
		}

		self::$files = new stdClass();
		self::$files -> html = array();
		self::$files -> style = array();
		self::$files -> script = array();

	}

	/**
	 * Get name of current template
	 * @param (string) current template
	 */
	public static function getName(){
		return self::$name;
	}

	/**
	 * Overwrite a basic template page
	 * @param $nt (string) name of page that will be overwritten
	 * @param $nf (string) name page that will overwrite
	 */
	public static function overwrite($nt,$nf){

		self::setInclude($nt,$nf);
	}

	/**
	 * Aggregate a page to another
	 * @param $nt (string) name of page that will be aggregated
	 * @param $nf (string) name page that will aggregated
	 * @param $pos (int) position of aggregation
	 */
	public static function aggregate($nt,$nf,$pos = null){
		if($pos == null || isset(self::$aggregate[$nt][$pos]))
			self::$aggregate[$nt][] = $nf;
		else
			self::$aggregate[$nt][$pos] = $nf;

	}

	/**
	 * Load a template
	 * @param $n (string) name of template
	 */
	public static function load($n){
		if(isset(self::$list[$n])){
			self::$name = $n;
			self::$path = self::$list[self::$name]."/";
		}

		$GLOBALS['style'] = TemplateEngine::loadStyles();
		$GLOBALS['script'] = TemplateEngine::loadScripts();
		$GLOBALS['path'] = "templates/{$n}/";

	}

	/**
	 * Compile all the page
	 * @param $pathSource (string) path where is located file .html to compile
	 * @param $subClass (string) name of "class of files"
	 */
	public static function compile($pathSource = '',$subClass = ''){

		if(empty($pathSource))
			$pathSource = self::$path;

		$pathCompiled = self::$path.self::$dirCompiled;

		if(!file_exists($pathCompiled))
			mkdir($pathCompiled);


		foreach(glob($pathSource.'*.html') as $k){

			if(!is_dir($k)){

				$b = basename($k,".html");
				if(!empty($subClass))$b = $subClass.".".$b;

				$fileCompiled = $pathCompiled."/".$b.".php";

				if(in_array($b,self::$compiled)){
					# some error, already compiled, conflicts etc..
				}

				self::$compiled[] = $b;

				# Check source of file
				$t = file_exists($k) && file_exists($fileCompiled) && filemtime($k) > filemtime($fileCompiled);

				# Per il momento è necessario che sia sempre attivo
				//if($t){
				if(true){
					

					if(file_exists($k)){
						$content = file_get_contents($k);
						$content = self::preCompile($k,$content,$subClass);
						$c = self::translate($k,$content);
					}else{
						# some error
					}
					

					file_put_contents($fileCompiled,$c);
				}
			}
		}

		if(!empty(self::$error)){
			self::printErrors(self::$error);
			die();
		}
	}

	/**
	 * Precompile the page
	 * @param $f (string) file name
	 * @param $c (string) content of the page
	 * @param $subClass (string) name of "class of files"
	 */
	private static function preCompile($f,$c,$subClass = ''){



		$b = empty($subClass) ? basename($f,".html") : $subClass.".".basename($f,".html");

		if(!empty(self::$aggregate[$b])){
			foreach(self::$aggregate[$b] as $k){
				$c .= "{{include $k}}";
			}
		}


		if(!empty($subClass)){
			# Include sub Class
			preg_match_all('/{{include \.([^\}]*)}}/iU',$c,$r);
			foreach($r[0] as $n => $k){

				$c = preg_replace($k,"{include ".$subClass.".".$r[1][$n]."}",$c);
				
			}
		}
		

		# Include
		preg_match_all('/{{include ([^\}]*)}}/iU',$c,$r);
		foreach($r[1] as $n => $k){

			if(empty(self::$include[$k]))
				TemplateEngine::setInclude($k,$k);
			
			$c = preg_replace('{'.$r[0][$n].'}','{{include TemplateEngine::getInclude(\''.$k.'\')}}',$c);

		}

		# Switch
		# Remove space between switch and first case
		$c = preg_replace('/{{switch ([^\}]*)}}([^\{]*){{case/iU',"{{switch $1}}\n{{case",$c);
		$c = preg_replace('/{{\/(case)}}([^\{]*){{(case)/iU','{{/case}}'."\n".'{{case',$c);
		$c = preg_replace('/{{\/(case)}}([^\{]*){{\/switch}}/iU','{{/case}}'."\n".'{{/switch}}',$c);
		return $c;
	}

	public static function setInclude($p,$f){
		self::$include[$p] = self::getNameInclude($f).".php";
	}

	public static function getInclude($p){
		return isset(self::$include[$p]) ? self::$include[$p] : $p.".php";
	}

	public static function parseSubClass($n){
		return strtolower($n);
	}

	public static function getNameInclude($n){
		return strtolower($n);
	}


	/**
	 * Translate the page
	 * @param $f (string) file name
	 * @param $c (string) content of the page
	 * @param (string) content translated
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
	 * @param $e (array) list of all error
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
	 * @param $page (string) name page
	 * @return (string) page
	 */
	public static function html($page){

		return self::$path.''.self::$dirCompiled.'/'.$page.'.php';
	}

	/**
	 * Load all styles
	 */
	public static function loadStyles(){
		return self::loadResources('style','css');
	}

	/**
	 * Load all scripts
	 */
	public static function loadScripts(){
		return self::loadResources('script','js');
	}

	/**
	 * Load all scripts
	 * @param $folder (string) name of folder
	 * @param $ext (string) type of file
	 */
	public static function loadResources($folder,$ext){

		$r = array();
		$path = self::$basePath.'/'.self::$name.'/'.$folder.'/src';
		$name = self::$name;

		foreach(glob($path.'/*') as $k){
			if(!is_dir($k))
				self::$files -> style[] = basename($k);
		}


		if(TMPL_CACHE >= 0){
			$r[] = self::loadResource(
				$name,
				$folder.'/cache/'.
				basename(self::cacheSystem($path,$ext,self::$files -> style))
			);

		}else{
			foreach(self::$files -> style as $k)
				$r[] = self::loadResource($name,$folder.'/src/'.basename($k));
		}


		return implode($r,"");
	}

	/**
	 * Load a resource file
	 * @param $name (string) name of template
	 * @param $page (string) path file
	 */
	public static function loadResource($name,$page){
		$ext = pathinfo($page, PATHINFO_EXTENSION);
		switch($ext){
			case 'css':
				return "<link rel='stylesheet' href='templates/{$name}/{$page}'>";
			break;

			case 'js':
				return "<script src='templates/{$name}/{$page}'></script>";
			break;
		}
		
	}

	/**
	 * Minify CSS code
	 * @param $s (string) css code
	 * @return (string) css minified
	 */
	public static function minifyCSS($s){
		

		$r1 = "
			(?sx)
			  (
				\"(?:\\[^\"\\]++|\\.)*+\"
			  | '(?:\\[^'\\]++|\\.)*+'
			  )
			|
			  /\* (?> .*? \*/ )
		";

		$r2 = "
			(?six)
			  (
				\"(?:\\[^\"\\]++|\\.)*+\"
			  | '(?:\\[^'\\]++|\\.)*+'
			  )
			|
			  \s*+ ; \s*+ ( } ) \s*+
			|
			  \s*+ ( [*$~^|]?+= | [{};,>~+-] | !important\b ) \s*+
			|
			  ( [[(:] ) \s++
			|
			  \s++ ( [])] )
			|
			  \s++ ( : ) \s*+
			  (?!
				(?>
				  [^{}\"']++
				| \"(?:\\[^\"\\]++|\\.)*+\"
				| '(?:\\[^'\\]++|\\.)*+' 
				)*+
				{
			  )
			|
			  ^ \s++ | \s++ \z
			|
			  (\s)\s+
		";

		$s = preg_replace("%$r1%", '$1', $s);
	   	$s = preg_replace("%$r2%", '$1$2$3$4$5$6$7', $s);
	   	return $s;
	}


	/**
	 * Minify JS code
	 * @param $s (string) js code
	 * @return (string) js minified
	 */
	public static function minifyJS($s){
	   	return $s;
	}

	/**
	 * Manage cached file
	 * @param $src (string) base path
	 * @param $ext (string) type of file (e.g. css/js)
	 * @param $files (array) array of flie to include
	 * @return (string) final path of file cache
	 */
	public static function cacheSystem($src,$ext,$files){

		$base = dirname($src)."/cache";
		$cacheTXT = $base."/.cache.txt";
		$nameCache = "/.cache-s";


		// Creo la lista dei file cache nel caso in cui non esita
		if(!file_exists($cacheTXT)){
			if(!file_exists(dirname($cacheTXT)))
				mkdir(dirname($cacheTXT), 0777, true);
			
			$fp = fopen($cacheTXT,"w");
			fclose($fp);
			file_put_contents($cacheTXT,json_encode(array()));
		}
			
		$cache = json_decode(file_get_contents($cacheTXT),true);
			
		// Controllo se il file cache esiste già
		if(in_array(json_encode($files),$cache)){
			$p = array_search(json_encode($files),$cache);
			$path = $base."{$nameCache}{$p}.{$ext}";
			if(file_exists($path) && time() - filemtime($path) < TMPL_CACHE)
				return $path;
		}
			
		// Leggo tutti i file  e li metto in una stringa
		$s = '';
		foreach($files as $k){
			if(file_exists($src."/".$k))
				$s .= file_get_contents($src."/".$k);
		}

		// Calcolo il nome nel caso in cui non esista
		if(!isset($p)){
			do{
				$p = substr(md5(microtime()),0,8);
			}while(isset($cache[$p]));
			$path = $base."/{$nameCache}{$p}.{$ext}";
		}

		// Creo il file nel caso in cui non esista
		if(!file_exists($path)){
			$fp = fopen($path,"w");
			fclose($fp);
		}

		// Aggiorno il contenuto del file
		switch($ext){
			case "css": file_put_contents($path,self::minifyCSS($s)); break;
			case "js": file_put_contents($path,self::minifyJS($s)); break;
		}
		

		// Aggiorno la lista di tutti i file cache
		$cache[$p] = json_encode($files);
		file_put_contents($cacheTXT,json_encode($cache));

		// Ritorno il collegamento html con il file cache
		return $path;
	}

}

?>