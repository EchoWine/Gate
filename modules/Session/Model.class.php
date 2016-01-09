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
	 * Add operation
	 */
	public $add = false;

	/**
	 * Edit operation
	 */
	public $edit = false;

	/**
	 * Copy operation
	 */
	public $copy = false;
		
	/**
	 * Controller Auth class
	 */
	public $auth = null;

	/**
	 * Set auth
	 * @param $auth (Auth object)
	 */
	public function setAuth($auth){
		$this -> auth = $auth;
	}

	/**
	 * Initialize field
	 */
	public function iniField(){

		$this -> setFields([
			new \SID([
				'name' => 'sid',
				'column' => $this -> auth -> cfg['session']['col']['sid'],
			]),
			new \UID([
				'name' => 'uid',
				'column' => $this -> auth -> cfg['session']['col']['uid'],
			]),
			new \Username([
				'name' => 'user',
				'column' => \Item::getObj('credential') -> getField('user') -> getColumnName(),
			]),
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

	/**
	 * Get QueryBuilder select
	 */
	public function getQuerySelect(){

		$c = \Item::getObj('credential');

		return \DB::table($this -> tableName) -> leftJoin(
			$c -> tableName,
			$this -> getField('uid') -> getColumnName(),
			$c -> getField('id') -> getColumnName()
		);
	}
}

?>