<?php

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
	 * Log
	 */
	public static $log;

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
		if(isset(self::$list[$n]))
			self::$name = $n;
	}

	/**
	 * Main function that print the page
	 * @return (string) page
	 */
	public static function html(){
		echo self::loadHTML('html');
	}

	/**
	 * Load a html file
	 * @param $page (string) name of file
	 * @return (string) content of file
	 */
	public static function loadHTML($page){

		self::$files -> html[] = $page;

		$path = self::$list[self::$name]."/".$page.".html";
		if(file_exists($path)){

			// Get content and replace variables
			$page = file_get_contents($path);
			
			$page = preg_replace("/{{([^\]\[]*)}}/iU","{\$GLOBALS['TEMPLATE']['$1']}",$page);
		
			// Elaborate content
			@ob_start();
				echo $page;
				$c = ob_get_contents();
				$c1 = addcslashes($c, '"');
			@ob_end_clean();

			@ob_start();
				eval("\$c1=\"$c1\";");
				$c2 = ob_get_contents();

				echo $c2;
			@ob_end_clean();

			// Print an error if a variable is not defined
			if(preg_match("/<b>Notice<\/b>:  Undefined index: (.*) in <b>(.*)<\/b> on line <b>(.*)<\/b>/",$c2,$res)){
				$err_title = 'Template Engine - Error';
				$err_file = "<b>".basename($path)."</b>, row <b>".$res[3]."</b>";
				$err_undefined = "<b>".$res[1]."</b> not defined";
				echo "
					<div style='border: 1px solid black;margin: 0 auto;padding: 20px;'>
						<h1>{$err_title}</h1>
						{$err_file}<br>
						{$err_undefined}
					</div>";
				die();
			}

			return $c1;


			self::$log[] = "Loaded: ".basename($path);
		}else{
			self::$log[] = "Not Loaded: ".basename($path);
		}
	}


}

?>