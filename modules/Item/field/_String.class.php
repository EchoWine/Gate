<?php

class _String extends Field{

	/**
	 * Label
	 */
	public $label = '[String name]';

	/**
	 * Pattern
	 */
	public $pattern = "/(.*)/iU";

	public function getInputData(){
		return self::$template.'._String';
	}

}
?>