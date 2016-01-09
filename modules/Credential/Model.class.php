<?php

namespace Item\Credential;

class Model extends \Item{
		
	/**
	 * Name
	 */
	public $name = 'credential';
		
	/**
	 * Label
	 */
	public $label = 'Credential';

	/**
	 * Initialize field
	 */
	public function iniField(){
		$this -> setFields([
			new \ID('id'),
			new \Username('user'),
			new \Password('pass'),
			new \Mail('mail')
		]);
		
		$this -> setFieldPrimary('id');

		$this -> setFieldLabel('user');
	}

}

?>