<?php

namespace CoreWine\Http;

//use InvalidArgumentException;
//namespace CoreWine\Facility\Response;


/**
 *
 * Represents a Cookie.
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
     * @var string ASCII codes not valid for for use in a cookie name
     *
     * Cookie names are defined as 'token', according to RFC 2616, Section 2.2
     * A valid token may contain any CHAR except CTLs (ASCII 0 - 31 or 127)
     * or any of the following separators
     */
	protected static $invalidChars;

	/**
     * Default cookie properties
     *
     * @var array
     */
    protected $defaults = [
        'value' => '',
        'domain' => null,
        'hostonly' => null,
        'path' => null,
        'expires' => null,
        'secure' => false,
        'httponly' => false
    ];

    /**
     * Cookie
     *
     * @var array
     */
    protected $cookies = [];


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
		if (empty($name) && !is_numeric($name)) {
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

		// path
		if (!isPathValid($path)) {
			throw new \InvalidArgumentException("Invalid Cookie's domain path.");
		}

		// All values are valid, but some may be empty. Let's use the default values
		// provided by the configuration file.
		//$this -> initialize($default_values);

		// This is the only time an inline, one-to-one assignment is tollerated.
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

	/**
	 *
	 * Perform some booting operations.
	 *
	 * @param array $default_values default key-value pair array.
	 */
	public function initialize($default_values = []) {

		if ($default_values === null || empty($default_values)) {
			throw new \InvalidArgumentException("Invalid Cookie's default values.");
		}

		// check structure conformity and (when needed) field definition conformity

		$this -> setDefaults($default_values);

		
	}

	/**
	 *
	 * Set cookie fields to default values as defined in its dedicated configuration file. This does not set the name since it's required.
	 *
	 * @return null
	 */
	private function setDefaults(array $default_values = null) {

		if (empty($default_values) || $default_values === null) {
			foreach ($this -> defaults as $key => $value) {
				$this -> cookies[$key] = $this -> defaults[$value];
			}
		} else {

			//$this -> name = $default_values['name'];
			$this -> value = $default_values['value'];
			$this -> expire = $default_values['expire'];
			$this -> path = $default_values['path'];
			$this -> domain = $default_values['domain'];
			$this -> secure = $default_values['secure']; 

		}

	}

	/**
	 *
	 * Set cookie 
	 *
	 * @param string $name Cookie name
	 * @param string $value Cookie value
	 */
	public function set($name, $value) {
		// check $value, $name

		$this -> cookies[$name] = $value;

		return $this;
	}




}