<?php

namespace CoreWine\Response;
//namespace CoreWine\Facility\Response;


/**
 *
 * Cookie
 *
 */
class Cookie {

	/**
	 * @var string 
	 */
	protected $name;

	/**
	 * @var string 
	 */
	protected $value;

	/**
	 * @var int|string  
	 */
	protected $expire;

	/**
	 * @var string  
	 */
	protected $path;

	/**
	 * @var string  
	 */
	protected $domain;

	/**
	 * @var bool  
	 */
	protected $secure;


	/**
	 *
	 * Constructor.
	 *
	 * All parameters as defined by the official PHP documentation. 
	 *
	 * @param string $name
	 * @param string $value
	 * @param int|string $expire
	 * @param string $path
	 * @param string $domain
	 * @param bool $secure
	 *
	 */
	public function __construct($name, $value = null, $expire = 0, $path = '/', $domain, $secure = false) {

		// Cookie's name is required.
		if (empty($name)) {
			throw new \InvalidArgumentException("Cookie's name is required.");
		}

		// check name's validity
		if (!isNameValid($name)) {
			throw new \InvalidArgumentException("Invalid Cookie's name.");
		}

		// expiration
		if (!isExpirationValid($expire)) {
			throw new \InvalidArgumentException("Invalid Cookie's expiration time.");
		}

		// expiration
		if (!isPathValid($expire)) {
			throw new \InvalidArgumentException("Invalid Cookie's domain path.");
		}



		$this -> name = $name;
		$this -> value = $value;
		$this -> expire = $expire;
		$this -> path = $path;
		$this -> domain = $domain;
		$this -> secure = $secure;
	}


	/**
	 *
	 * Is Cookie's expiration time valid?.
	 *
	 * @param int|string $expiration
	 * @return bool true if valid
	 */
	public function isExpirationValid($expiration) {
		if (!is_numeric($expiration)) {
			$expiration = strtotime($expiration);
		}

		if ($expiration === false ||
			$expiration === null) {
			return false;
		}
		// compare it to some dedicate object value

		return true;
	}

	/**
	 *
	 * Is Cookie's name valid?.
	 *
	 * @param string $name
	 * @return bool true if valid
	 */
	public function isNameValid($name) {
		// use some static validation from a dedicated class in here
		return true;
	}

	/**
	 *
	 * Is Cookie's path valid?.
	 *
	 * @param string $path
	 * @return bool true if valid
	 */
	public function isPathValid($path) {
		// use some static validation from a dedicated class in here
		// regex only?
		return true;
	}

	/**
	 *
	 * Retrieve cookie's name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this -> name;
	}

	/**
	 *
	 *  Retrieve cookie's expiration time.
	 *
	 * @return string
	 */
	public function getExpirationTime() {
		return $this -> expire;
	}

	/**
	 *
	 * Retrieve cookie's value.
	 *
	 * @return string
	 */
	public function getValue() {
		return $this -> value;
	}

	/**
	 *
	 * Retrieve cookie's applicable domain.
	 *
	 * @return string
	 */
	public function getDomain() {
		return $this -> domain;
	}

	/**
	 *
	 * Retrieve cookie's applicable path.
	 *
	 * @return string
	 */
	public function getPath() {
		return $this -> path;
	}

	/**
	 *
	 * Retrieve cookies HTTPS connection flag.
	 *
	 * @return bool
	 */
	public function isSecure() {
		return $this -> secure;
	}





	// transform cookie to string type
	public function __toString() {}

	// transform cookie to int type
	public function toInteger() {}

	// a possibility
	public function toCustomType() {}




}