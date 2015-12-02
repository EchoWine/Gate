<?php

/**
 * Time of caching:
 * -1 : Disabled
 * >= 0: Enabled
*/
define('TMPL_CACHE',0);

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
	 * Load a template
	 * @param $n (string) name of template
	 */
	public static function load($n){
		if(isset(self::$list[$n])){
			self::$name = $n;
			self::$path = self::$list[self::$name]."/";
		}

		$GLOBALS['style'] = TemplateEngine::loadAllStyle();

		TemplateEngine::compile();
	}

	/**
	 * Compile all the page
	 */
	private static function compile(){

		$pathCompiled = self::$path.self::$dirCompiled;

		if(!file_exists($pathCompiled))
			mkdir($pathCompiled);


		foreach(glob(self::$path.'/*') as $k){
			if(!is_dir($k)){
				$fileCompiled = $pathCompiled."/".basename($k,".html").".php";

				if(!file_exists($fileCompiled) || filemtime($k) > filemtime($fileCompiled)){
					$c = file_get_contents($k);
					$c = self::translate($c);
					file_put_contents($fileCompiled,$c);
				}
			}
		}
	}

	/**
	 * Translate the page
	 * @param $c (string) content of the page
	 * @param (string) content translated
	 */
	private static function translate($c){


		preg_match_all('/{{(?!#)([^\}]*)}}/iU',$c,$r);

		foreach($r[1] as $n => $k){
			$k = preg_replace('/\.([\w]*)/','[\'$1\']',$k);
			$c = str_ireplace($r[0][$n],'<?php echo $'.$k.'; ?>',$c);
		}

		$a = array(
			'/{{#include ([^\}]*)}}/iU',
			'/{{#for ([^\} ]*) as ([^\}]*)}}/iU',
			'/{{#endfor}}/iU',
		);
		$r = array(
			'<?php include \'$1.php\';?>',
			'<?php foreach(\$$1 as \$$2){ ?>',
			'<?php } ?>',
		);
		return preg_replace($a,$r,$c);
	}

	/**
	 * Main function that print the page
	 * @return (string) page
	 */
	public static function html(){
		return self::$path.''.self::$dirCompiled.'/html.php';
	}

	/**
	 * Load all style
	 */
	public static function loadAllStyle(){

		$r = array();
		$path = self::$basePath.'/'.self::$name.'/style/src';
		$name = self::$name;

		foreach(glob($path.'/*') as $k){
			if(!is_dir($k))
				self::$files -> style[] = basename($k);
		}


		if(TMPL_CACHE >= 0){
			$a = self::loadStyle(
				$name,
				'cache/'.
				basename(self::cacheSystem($path,"css",self::$files -> style))
			);

			return $a;
		}else{
			$r = '';
			foreach(self::$files -> style as $k)
				$r[] = self::loadStyle($name,'src/'.basename($k));

			return $r;
		}


		return implode($r,",");
	}

	/**
	 * Load all style
	 * @param $page (string) name of css file
	 */
	public static function loadStyle($name,$page){
		return $path = "<link rel='stylesheet' href='templates/{$name}/style/{$page}'>";
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
			case "js": file_put_contents($path,self::minify_js($s)); break;
		}
		

		// Aggiorno la lista di tutti i file cache
		$cache[$p] = json_encode($files);
		file_put_contents($cacheTXT,json_encode($cache));

		// Ritorno il collegamento html con il file cache
		return $path;
	}

}

?>