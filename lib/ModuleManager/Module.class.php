<?php
class Module{
  	
  	public static $name;

	public static function load($path){

	}

	public static function TemplateOverwrite($n,$c){

		$sub = basename(dirname(debug_backtrace()[0]['file']));
		$sub = TemplateEngine::parseSubClass($sub);

		TemplateEngine::overwrite($n,$sub.".".$c);
	}

	public static function TemplateAggregate($n,$p,$c,$p){

		$sub = basename(dirname(debug_backtrace()[0]['file']));
		$sub = TemplateEngine::parseSubClass($sub);

		TemplateEngine::aggregate($n,$p,$sub.".".$c,30);
	}

}

?>