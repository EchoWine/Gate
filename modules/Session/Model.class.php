<?php

namespace Item\Session;

class Model extends \Item{
		
	/**
	 * Name
	 */
	public $name = 'session';
		
	/**
	 * Label
	 */
	public $label = 'Session';
		
	/**
	 * Controller Auth class
	 */
	public $auth = null;

	/**
	 * Construct auth
	 */
	public function __construct($auth){
		$this -> auth = $auth;
		$this -> ini();
	}

	/**
	 * Initialize field
	 */
	public function iniField(){
		$this -> setFields([
			new \ID([
				'name' => 'uid',
				'label' => 'UID'
			]),
			new \SID('sid')
		]);
		
		$this -> setFieldPrimary('sid');

		$this -> setFieldLabel('sid');
	}

	/**
	 * Retrieve table name
	 */
	public function retrieveTableName(){
		return $this -> auth -> cfg['session']['table'];
	}

}

?>