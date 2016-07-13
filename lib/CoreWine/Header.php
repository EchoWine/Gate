<?php

namespace CoreWine\Response;
//namespace CoreWine\Facility\Response;
use CoreWine\Response\HeaderIteratorInterface as HeaderInterface;


/**
 *
 * Header
 *
 */
class Header implements \iterator {

	/**
	 * @var int 
	 */
	private $index = 0;

	/**
	 * @var array 
	 */
	protected $headers = [];


	/**
	 *
	 * Constructor.
	 *
	 *
	 * @param array $headers
	 *
	 */
	public function __construct($headers = []) {
		if (!is_array($headers)) {
			// attempt to parse it as an object or a dedicated type
			// if failed, throw an error
		}


		foreach ($headers as $key => $value) {
			$this -> set($key, $value);
		}
	}

	/**
	 *
	 * Rewind header's index.
	 *
	 */
	function rewind() {
		$this -> index = 0;
	}

	/**
	 *
	 * Retrieve current header value. 
	 *
	 * @return string 
	 */
	function current() {
		return $this -> headers[$this -> index];
	}

	/**
	 *
	 * Retrieve current header key. 
	 *
	 * @return int 
	 */
	function key() {
		return $this -> index;
	}

	/**
	 *
	 * Retrieve next header value. 
	 *
	 * @return string null if there's no next header.
	 */
	function next() {
		$this -> index++;
		if (isset($this -> headers[$this -> index])) {
			return ($this -> headers[$this -> index]);
		} else {
			return null;
		}
	}

	/**
	 *
	 * Is the header valid?. 
	 *
	 * @return bool 
	 */
	function valid() {
		// value not set
		if (! isset($this -> headers[$this -> index])) {
			return false;
		}

		if (! isValueValid($this -> headers[$this -> index])) {
			return false;
		}

		return true;
	}

	/**
	 *
	 * Is the header value a valid one?. 
	 *
	 * @param string $value The header value 
	 * @return bool
	 */
	function isValueValid($value) {
		// match it up against some well know pattern
		return true;
	}

	/**
	 *
	 * Set header by key-value pair. 
	 *
	 * @param string $key
	 * @param string $value  
	 * @return bool 
	 */
	function set($key, $value) {
		$this -> headers[$key] = $value;
		
		return true;
	}





}