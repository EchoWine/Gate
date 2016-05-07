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
	 * PUT
	 */
	public static $REQUEST_PUT;

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

		self::iniPUT();
	}

	/** 
	 * Ini put
	 */
	public static function iniPUT(){
		parse_str(file_get_contents('php://input'), self::$REQUEST_PUT);
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
	 * Get $_POST
	 *
	 * @param string $name
	 * @return $_POST
	 */
	public static function post($name){
		return isset($_POST[$name]) ? $_POST[$name] : null;
	}

	/**
	 * Get $PUT
	 *
	 * @param string $name
	 * @return $_POST
	 */
	public static function put($name){
		return isset(Request::$REQUEST_PUT[$name]) ? Request::$REQUEST_PUT[$name] : null;
	}

	/**
	 * Get $_GET
	 *
	 * @param string $name
	 * @return $_GET
	 */
	public static function get($name){
		return isset($_GET[$name]) ? $_GET[$name] : null;
	}
	
	/**
	 * Get $_FILES
	 *
	 * @param string $name
	 * @return $_FILES
	 */
	public static function files($name){
		return isset($_FILES[$name]) ? $_FILES[$name] : null;
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

}

Request::ini();

?>