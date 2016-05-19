<?php

namespace CoreWine;

class Request{

	/**
	 * Force ssl
	 */
	const COOKIE_FORCE_SSL = false;

	/**
	 * HTTP Only
	 */
	const COOKIE_HTTP_ONLY = false;

	/** 
	 * Request method get
	 */
	const METHOD_GET = 'GET';

	/** 
	 * Request method post
	 */
	const METHOD_POST = 'POST';

	/** 
	 * Request method put
	 */
	const METHOD_PUT = 'PUT';

	/** 
	 * Request method delete
	 */
	const METHOD_DELETE = 'DELETE';

	/**
	 * GET
	 */
	public static $REQUEST_GET;

	/**
	 * PUT
	 */
	public static $REQUEST_PUT;

	/**
	 * POST
	 */
	public static $REQUEST_POST;

	/**
	 * FILES
	 */
	public static $REQUEST_FILES;

	/**
	 * Method of request
	 */
	public static $method;


	/**
	 * Initialization
	 */
	public static function ini(){

		// Prevents javascript XSS attacks aimed to steal the session ID
		ini_set('session.cookie_httponly', 1);

		// Session ID cannot be passed through URLs
		ini_set('session.use_only_cookies', 1);

		// Uses a secure connection (HTTPS) if possible
		// ini_set('session.cookie_secure', 1);

		self::startSession();

		self::$method = $_SERVER['REQUEST_METHOD'];

		self::ini_REQUEST_GET();
		self::ini_REQUEST_POST();
		self::ini_REQUEST_PUT();
		self::ini_REQUEST_FILES();
	}

	/** 
	 * Ini put
	 */
	public static function ini_REQUEST_GET(){
		Request::$REQUEST_GET = $_GET;
	}

	/** 
	 * Ini put
	 */
	public static function ini_REQUEST_POST(){
		Request::$REQUEST_POST = $_POST;
	}

	/** 
	 * Ini put
	 */
	public static function ini_REQUEST_PUT(){
		parse_str(file_get_contents('php://input'), Request::$REQUEST_PUT);
	}

	/** 
	 * Ini put
	 */
	public static function ini_REQUEST_FILES(){
		Request::$REQUEST_FILES = $_FILES;
	}

	/**
	 * Get all params in the request
	 */
	public static function getCall(){
		return [
			'url' => Request::getRelativeUrl(),
			'method' => Request::getMethod(),
			'get' => Request::$REQUEST_GET,
			'post' => Request::$REQUEST_POST,
			'put' => Request::$REQUEST_PUT
		];
	}

	/**
	 * Redirect to url
	 *
	 * @param string $url url
	 */
	public static function redirect($url){
		header("Location:".$url);
		die();
	}

	/**
	 * Refresh
	 *
	 * @param string $url url
	 */
	public static function refresh(){
		header("Location:".$_SERVER['REQUEST_URI']);
		die();
	}

	/**
	 * Get request get
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return $_GET
	 */
	public static function get($name,$default = null){
		return isset(Request::$REQUEST_GET[$name]) ? Request::$REQUEST_GET[$name] : $default;
	}
	

	/**
	 * Get request post
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return $_POST
	 */
	public static function post($name,$default = null){
		return isset(Request::$REQUEST_POST[$name]) ? Request::$REQUEST_POST[$name] : $default;
	}

	/**
	 * Get request put
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return $_POST
	 */
	public static function put($name,$default = null){
		return isset(Request::$REQUEST_PUT[$name]) ? Request::$REQUEST_PUT[$name] : $default;
	}
	/**
	 * Get request files
	 *
	 * @param string $name
	 * @return $_FILES
	 */
	public static function files($name){
		return isset(Request::$REQUEST_FILES[$name]) ? Request::$REQUEST_FILES[$name] : null;
	}

	/**
	 * Set a session
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public static function setSession($name,$value){
		$_SESSION[$name] = $value;
	}

	/**
	 * Get $_SESSION
	 *
	 * @param string $name
	 * @return $_SESSION
	 */
	public static function getSession($name){
		return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
	}

	/**
	 * Delete a $_SESSION
	 *
	 * @param string $name
	 */
	public static function unsetSession($name){
		unset($_SESSION[$name]);
	}

	/**
	 * Set a cookie
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public static function setCookie($name,$value,$expiry = null,$path = '/',$domain = null,$forceSSL = null,$httpOnly = null){

		if(!headers_sent()){

			if($forceSSL == null)
				$forceSSL = self::COOKIE_FORCE_SSL;
			
			if($httpOnly == null)
			   $httpOnly = self::COOKIE_HTTP_ONLY;
			
			if($domain == null)
				$domain = $_SERVER['SERVER_NAME'] == "localhost" ? null : $_SERVER['SERVER_NAME'];
		
			if($expiry == null)
				$expiry = time() + 60*60*24*365*10;

			return setcookie($name, $value, (int)$expiry, $path,null, $forceSSL, $httpOnly);
		}

		return false;	
	}

	/**
	 * Get a cookie
	 *
	 * @param string $name
	 * @return cookie
	 */
	public static function getCookie($name){
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}

	/**
	 * Delete a cookie
	 *
	 * @param string $name
	 * @param string $path
	 * @return bool result
	 */
	public static function unsetCookie($name,$path = '/'){
		unset($_COOKIE[$name]);
		return self::setCookie($name,null,-1,$path);
	}

	/**
	 * Start a session
	 */
	public static function startSession(){
		if(session_status() == PHP_SESSION_NONE)
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
	 * Get relative url
	 * 
	 * @return string relative url
	 */
	public static function getRelativeUrl(){
		return preg_replace("/(\?|&).*/",'',str_replace(dirname($_SERVER['PHP_SELF']),'',$_SERVER['REQUEST_URI']));
	}

	/**
	 * Get relative url
	 * 
	 * @return string relative url
	 */
	public static function getDirUrl(){
		return dirname($_SERVER['PHP_SELF'])."/";
	}

	/**
	 * Get method of the request
	 * 
	 * @return string method
	 */
	public static function getMethod(){
		return Request::$method;
	}

}

Request::ini();

?>