<?php

class Username extends _String{
		
	/**
	 * Username
	 */
	public $label = 'Username';

	/**
	 * Is unique value
	 */
	public $unique = true;

	/**
	 * Min length value
	 */
	public $minLength = 1;
}
?>