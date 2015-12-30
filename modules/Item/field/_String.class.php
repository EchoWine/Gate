<?php

class _String extends Field{
	
	public function iniLabel(){
		$this -> label = '[String name]';
	}

	public function getInputData(){
		return self::$template.'._String';
	}

}
?>