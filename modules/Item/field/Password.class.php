<?php

class Password extends _String{
	
	/**
	 * Min length value
	 */
	public $minLength = 1;

	/**
	 * Initialize label
	 */
	public function iniLabel(){
		$this -> label = 'Password';
	}

	/**
	 * Initialize print
	 */
	public function iniPrint(){
		$this -> print = (object)[
			'list' => null,
			'get' => null,
			'form' => $this -> label,
			'value' => false
		];
	}

	/**
	 * Prepare value field to query
	 * @param $v (mixed) value of field
	 * @param (mixed) value prepared
	 */
	public function dbValue($v){
		return AuthModel::getHashPass($v);
	}
}
?>