<?php

class Password extends _String{
	

	/**
	 * Print the value in the input
	 */
	public $printInputValue = false;

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
			'view' => null,
			'form' => $this -> label,
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