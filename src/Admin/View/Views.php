<?php

namespace Admin\View;

use CoreWine\Exceptions as Exceptions;

class Views{

	/**
	 * List of all views
	 *
	 * @var array
	 */
	public $views = [];

	/**
	 * Schema
	 *
	 * @var ORM\Schema
	 */
	public $schema;


	/**
	 * Construct
	 */
	public function __construct($schema){
		$this -> schema = $schema;
	}

	/**
	 * Get schema
	 *
	 * @return ORM\Schema
	 */
	public function getSchema(){
		return $this -> schema;
	}

	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	public function __call($method,$arguments){

		$view = new View($this -> getSchema());
		$arguments[0]($view);

		$this -> views[$method] = $view;

	}

	/**
	 * Return view by name
	 *
	 * @param string $view
	 *
	 * @return bool
	 */
	public function view($view){
		return $this -> views[$view] -> getFields();
	}

	/**
	 * Return if exists $view
	 *
	 * @param string $view
	 *
	 * @return bool
	 */
	public function is($view){
		return isset($this -> views[$view]);
	}
}
?>