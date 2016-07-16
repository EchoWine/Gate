<?php

namespace CoreWine\Response\Http;
//namespace CoreWine\Facility\Response;
use CoreWine\Response\HeaderIteratorInterface as HeaderInterface;
// use CoreWine\Utils\DateTime as DateTime;

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
			throw new \InvalidArgumentException("Invalid headers provided.");
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
	 * Retrieves a header's values by name
	 *
	 * @param string $key 			The name of the header
	 * @return array 				The name's corresponding values
	 */
	function get($key) {
		$key = str_replace('_', '-', strtolower($key));

		// is $key defined?
		if (!array_key_exists($key, $this -> headers)) {
			throw new \InvalidArgumentException("Invalid key provided.");
		}

		return $this -> headers[$key];
	}

	/**
	 * Returns the first value of the header provided by name
	 *
	 * @param string $key 			The name of the header
	 * @return string 				The first value of the header
	 */
	function first($key) {
		// @todo check if $key is valid

		return $this -> headers[$key][0];
	}

	/**
	 * Sets (a) header value(s)
	 *
	 * @param string $key 				The header's key
	 * @param string|array $value 		The header's value
	 */
	function set($key, $value) {
		$key = str_replace('_', '-', strtolower($key));

		// if is not already, make $value an array
		if (!is_array($value)) {
			$value = (array) $value;
		}

		$this -> headers[$key] = array_merge($this -> headers[$key], array_values($value));

		// @todo: cache, for non API versions

	}

	/**
	 * Returns all the headers
	 *
	 * @return array 				The headers as an array
	 */
	function all() {
		return $this -> headers;
	}

	/**
	 * Add new headers
	 *
	 * @param array $headers 		An array of headers
	 */
	function add($headers = []) {

		// @todo add non array header type transformation and eventual validation (i.e. An JSON object containing the headers)

		if (!is_array($headers)) {
			throw new \InvalidArgumentException("Invalid headers provided.");
		}

		foreach ($headers as $key => $value) {
			$this -> set($key, $value);
		}
	}


	/**
	 * Delete a header by name
	 *
	 * @param string $key 		The name of the header
	 */
	function delete($key) {
		$key = str_replace('_', '-', strtolower($key));

		if ($this -> has($key)) {
			unset($this -> headers[$key]);
		}

		// do something otherwise
	}
	

	/**
	 * Returns the headers as a string
	 *
	 * @return string 				The headers as a string
	 */
	function __toString() {
		// @todo headers to string conversion
		return '';
	}


	/**
	 * Replaces the current headers
	 *
	 * @param array $headers 		An array containing the headers
	 */
	function setAll($headers = []) {
		// is $headers valid?
		if (!is_array($headers)) {
			throw new \InvalidArgumentException("Headers must be an array.");
		}

		// @todo an verify all function
		// verify each value
		foreach ($headers as $key => $value) {
			if (!isValueValid($value)) {
				throw new \InvalidArgumentException("Invalid header value provided.");
			}	
		}

		// discart the current headers and set the new values
		$this -> headers = [];
		$this -> add($headers);


		//foreach ($headers as $key => $value) {
		//	$this -> set($key, $value);	
		//}

	}

	/**
	 * Is $key defined in the headers?
	 *
	 * @param string $key 		The name of the header
	 * @return bool 				True if $key exist, false otherwise
	 */
	function has($key, $value = null) {
		$key = str_replace('_', '-', strtolower($key));

		if ($this -> has($key)) {
			if ($value === null) {
				return array_key_exists($key, $this -> headers);
			} else {
				return in_array($value, $this -> get($key));
			}
		}

		// header is not defined
		return false;
	}

	/**
	 * Returns header's number
	 *
	 * @return int 				The header's total number
	 */
	function count() {
		return count($this -> headers);
	}


	//function date(){}
	// cache methods will go on dedicated extension
	//



}