<?php

class SID extends _String{
		
	/**
	 * SID
	 */
	public $label = 'SID';

	/**
	 * Is unique value
	 */
	public $unique = true;

	/**
	 * Basic pattern
	 */
	public $_pattern = "([a-z0-9])";
}
?>