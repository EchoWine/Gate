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
	 * Controller Auth class
	 */
	public $auth = null;

	/**
	 * Set auth
	 *
	 * @param Auth object $auth
	 */
	public function setAuth($auth){
		$this -> auth = $auth;
	}

	/**
	 * Initialize field
	 */
	public function iniField(){
		$this -> setFields([

			new \ID([
				'name' => 'id',
				'column' => $this -> auth -> cfg['credential']['col']['id'],
			]),
			new \Username([
				'name' => 'user',
				'column' => $this -> auth -> cfg['credential']['col']['user'],
			]),
			new \Password([
				'name' => 'pass',
				'column' => $this -> auth -> cfg['credential']['col']['pass'],
			]),
			new \Mail([
				'name' => 'mail',
				'column' => $this -> auth -> cfg['credential']['col']['mail'],
			])
		]);
		
		$this -> setFieldPrimary('id');

		$this -> setFieldLabel('user');
	}

}

?>