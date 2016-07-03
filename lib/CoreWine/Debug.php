<?php

namespace CoreWine;

/**
 * A helper class for debugging.
 */
class Debug{

	public static $data;

	/**
	 * Add to stack.
	 *
	 * @param DataType $data
	 */
	public static function add($data){
		Debug::$data[] = $data;
	}

	/**
	 * Print the stack.
	 */
	public static function print(){
		print_r(Debug::$data);
	}
}

?>