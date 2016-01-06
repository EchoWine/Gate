<?php
/**
 * Cookie and session managment
 */
class Cookie{

	public static $CONFIG;

	/**
	 * Initialization
	 */
	public static function ini(){


		// Source: http://stackoverflow.com/questions/22221807/session-cookies-http-secure-flag-how-do-you-set-these
		// **PREVENTING SESSION HIJACKING**
		// Prevents javascript XSS attacks aimed to steal the session ID
		ini_set('session.cookie_httponly', 1);

		// **PREVENTING SESSION FIXATION**
		// Session ID cannot be passed through URLs
		ini_set('session.use_only_cookies', 1);

		// Uses a secure connection (HTTPS) if possible
		// ini_set('session.cookie_secure', 1);

		self::startSession();
	}

	/**
	 * Start a session
	 */
	public static function startSession(){

		session_start();
	}
			
	/**
	 * Destroy a session
	 */
	public static function destroySession(){
		session_unset();
		session_destroy();
	}


	/**
	 * Unset session
	 * 
	 * @param string $name	  Session name
	 */
	public static function sessionUnset($name){
		unset($_SESSION[$name]);
	}

	/**
	 * Check if the cookie exists
	 * 
	 * @param $name	 Cookie name
	 * @return bool	 True if exists, else otherwise
	 */
	public static function exist($name){
		return isset($_COOKIE[$name]);
	}

	/**
	 * Check if the cookie is empty or if it doesn't exist
	 * 
	 * @param $name			Cookie name
	 * @return bool			True if is empty or if it doens't exists, false otherwise
	 */
	public static function isEmpty($name){
		$cookie = self::getCookie($name);
		return empty($cookie);
	}

	/**
	 * Return a session value
	 * 
	 * @param string $name	  Session name
	 * @param string $default  [optional] Default value if the session doesn't exist
	 * @return string		  Session value
	 */
	public static function getSession($name, $default = ''){
		return (isset($_SESSION[$name]) ? $_SESSION[$name] : $default);
	}
	
	/**
	 * Set or update a session
	 * 
	 * @param string $name	  Session name
	 * @param string $value	  Session value
	 */
	public static function setSession($name, $value){
		$_SESSION[$name] = $value;
	}
	
	/**
	 * Return the value of a cookie or a session
	 * 
	 * @param string $type	  Session or cookie
	 * @param string $name	  Session/cookie name
	 * @param string $default  [optional] Default value if the cookie/session doesn't exist
	 * @return string		  Session/cookie value
	 */
	public static function get($type, $name, $default){
		switch($type){
			case 'cookie':
				$returnCode = self::getCookie($name, $default);
				break;
			case 'session':
				$returnCode = self::getSession($name, $default);
				break;
		}
		return $returnCode;
	}   
	
	/**
	 * Set a cookie or a session
	 * 
	 * @param string $type	       Session or cookie
	 * @param string $name	       Session/cookie name
	 * @param string $value	       Session/cookie value
	 * @param int $expiry	       [cookie only][optional] Duration of a cookie (example: one day)
	 * @param string $path	       [cookie only][optional] Cookie path
	 * @param bool $forceSSL      [cookie only][optional] Cookie is valid only on SSL connection
	 * @param bool $httpOnly      [cookie only][optional] Cookie valid only inside HTTP calls (no JavaScript)
	 * @return bool		       True if there is an error, false otherwise
	 */
	public static function set($type, $name, $expiry = 86400, $path = '/', $forceSSL = '', $httpOnly = ''){
		switch($type){
			case 'cookie':
				$returnCode = self::setCookie($name, $type, $expiry, $path, $forceSSL, $httpOnly);
				break;
			case 'session':
				$returnCode = self::setSession($name, $type);
				break;
		}
		return $returnCode;
	} 
	
	/**
	 * Return the value of a cookie
	 * 
	 * @param string $name	    Cookie name
	 * @param string $default   [optional] Default value if the cookie doesn't exist
	 * @return string		    Cookie value
	 */
	public static function getCookie($name, $default = ''){
		return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default);
	}

	/**
	 * Set or update a cookie
	 * 
	 * @param string $name  Cookie name
	 * @param string $value	Cookie value
	 * @param int $expiry	       [cookie only][optional] Duration of a cookie (example: one day)
	 * @param string $path	       [cookie only][optional] Cookie path
	 * @param bool $forceSSL      [cookie only][optional] Cookie is valid only on SSL connection
	 * @param bool $httpOnly      [cookie only][optional] Cookie valid only inside HTTP calls (no JavaScript)
	 * @return bool		       True if there is an error, false otherwise
	 */
	public static function setCookie($name, $value, $expiry = -1, $path = '/', $forceSSL = '', $httpOnly = ''){

		if(!headers_sent()){ # Impedisce l'invio dei cookie se sono già stati inviati degli header

			# Se non è stato assegnato alcun valore a $forceSSL, controlla nel config
			if($forceSSL == ''){
				$forceSSL = self::$CONFIG['FORCE_SSL'];
			}

			# Se non è stato assegnato alcun valore a $httpOnly, controlla nel config
			if($httpOnly == ''){
			   $httpOnly = self::$CONFIG['COOKIES_HTTP_ONLY'];
			}
			
			# Ottiene il dominio tramite la classe 'information' <-- deprecated
			$domain = $_SERVER['SERVER_NAME'] == "localhost" ? NULL : $_SERVER['SERVER_NAME'];
			
			$expiry = (int)$expiry;

			# Always
			if($expiry === -1)
				$expiry = time() + 60*60*24*365*10;

			return setcookie($name, $value, $expiry, $path, $domain, $forceSSL, $httpOnly);
		}

		return false;			
	}

	/**
	 * Delete a cookie
	 *
	 * @param string $name		  Cookie name
	 * @param string $path		  [cookie only][optional] Cookie path
	 * @param bool $removeNow	  If true remove cookie without reloading the page  
	 * @return bool		          True if there is an error, false otherwise
	 */
	public static function removeCookie($name, $path = '/', $removeNow = false){
		$returnCode = false;
		$returnCode = self::setCookie($name, '', time() - 3600, $path);
		if ($removeNow){
			unset($_COOKIE[$name]);
		}
	
		return $returnCode;
	}

	/**
	 * Delete a session
	 * 
	 * @param string $name Session name
	 */
	public static function removeSession($name){
		unset($_SESSION[$name]);
	}
	
}

?>