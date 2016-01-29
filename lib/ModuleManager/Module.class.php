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
		}

		TemplateEngine::setInclude($n,$c,$sub);
	}

	public static function TemplateAggregate($n,$c,$pos,$sub = null){

		if($sub === null){
			$sub = basename(dirname(debug_backtrace()[0]['file']));
		}

		TemplateEngine::addJoin($n,$c,$sub,$pos);
	}



}

?>