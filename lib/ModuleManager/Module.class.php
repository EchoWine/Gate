<?php
class Module{

	/**
	 * Name of model
	 */
	public $name;

	/**
	 * Construct
	 *
	 * @param string $n name of model
	 */
	public function __construct($n){
		$this -> name = $n;
	}

	public static function getNameModule($path){
		get_name_class();
	}

	public static function TemplateOverwrite($n,$c,$sub = null){

		if($sub === null){
			$sub = basename(dirname(debug_backtrace()[0]['file']));
			$sub = TemplateEngine::parseSubClass($sub);
		}

		TemplateEngine::overwrite($n,$sub.".".$c);
	}

	public static function TemplateAggregate($n,$c,$pos,$sub = null){

		if($sub === null){
			$sub = basename(dirname(debug_backtrace()[0]['file']));
			$sub = TemplateEngine::parseSubClass($sub);
		}

		TemplateEngine::aggregate($n,$sub.".".$c,$pos);
	}



}

?>