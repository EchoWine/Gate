<?php
class Module{

	/**
	 * Name of model
	 */
	public $name;

	/**
	 * Construct
	 * @param $n (string) name of model
	 */
	public function __construct($n){
		$this -> name = $n;
	}
	
	public static function load($path){

	}

	public static function getNameModule($path){
		get_name_class();
	}

	public static function TemplateOverwrite($n,$c){

		$sub = basename(dirname(debug_backtrace()[0]['file']));
		$sub = TemplateEngine::parseSubClass($sub);

		TemplateEngine::overwrite($n,$sub.".".$c);
	}

	public static function TemplateAggregate($n,$c,$pos){

		$sub = basename(dirname(debug_backtrace()[0]['file']));
		$sub = TemplateEngine::parseSubClass($sub);

		TemplateEngine::aggregate($n,$sub.".".$c,$pos);
	}

}

?>