<?php

namespace Auth\Service;

use CoreWine\DataBase\DB;
use CoreWine\Cfg;
use CoreWine\Request;

use Auth\Repository\AuthRepository;
use Auth\Entity\User;
use Auth\Entity\Session;

class Auth{

	/**
	 * Auth\Entity\User
	 */
	public static $user = null;

	/**
	 * Auth\Entity\Session
	 */
	public static $session = null;

	/**
	 * Initilization
	 */
	public static function ini(){
		AuthRepository::alterSchema();
		Auth::createFirstUser();
		AuthRepository::removeSessionExpired();
		Auth::checkSession();
	}

	/**
	 * Create first user if is empty
	 */
	public static function createFirstUser(){		
		if(AuthRepository::user() -> count() == 0){
			AuthRepository::user() -> insert([
				'username' => Cfg::get('Auth.default.username'),
				'email' => Cfg::get('Auth.default.email'),
				'password' => Auth::getHashPass(Cfg::get('Auth.default.password'))
			]);
		}
	}

	/**
	 * Check if current user is logged
	 */
	public static function checkSession(){

		$sid = Auth::getSidByCookie();

		if(!empty($sid)){

			$user = AuthRepository::getUserBySID($sid);

			if($user !== null){
				
				# Temporary, i guess
				Auth::$user = new User();
				Auth::$user -> id = $user['user_id'];
				Auth::$user -> username = $user['username'];
				Auth::$user -> password = $user['password'];
				Auth::$user -> email = $user['email'];
				Auth::$session = new Session();
				Auth::$session -> sid = $user['sid'];
				Auth::$session -> user_id = $user['user_id'];
				Auth::$session -> expire = $user['expire'];
				

			}else
				Request::unsetCookie(Cfg::get('Auth.cookie'));
			
		}

		return [];
	}

	/**
	 * Get entity User
	 *
	 * @return Auth\Entity\User
	 */
	public static function user(){
		return Auth::$user;
	}

	/**
	 * Get entity Session
	 *
	 * @return Auth\Entity\Session
	 */
	public static function session(){
		return Auth::$session;
	}

	/**
	 * User is logged
	 *
	 * @return bool
	 */
	public static function logged(){
		return Auth::user() !== null;
	}

	/**
	 * Logout current user
	 */
	public static function logout(){

		# Delete from table
		AuthRepository::deleteSessionBySID(Auth::session() -> SID);

		# Delete from cookies
		Request::unsetCookie(Cfg::get('Auth.cookie'));
		Request::unsetSession(Cfg::get('Auth.cookie'));

	}

	/**
	 * Login
	 *
	 * @param array $user
	 * @param cfg $type
	 */
	public static function login($user,$type){

		$sid = AuthRepository::generateSID();
		$expire = time()+$type['expire'];
		AuthRepository::session() -> insert([
			'user_id' => $user['id'],
			'sid' => $sid,
			'expire' => $expire
		]);

		if($type['data'] == 0)
			Request::setCookie(Cfg::get('Auth.cookie'),$sid,$expire);
		else
			Request::setSession(Cfg::get('Auth.cookie'),$sid);

	}
	
	/**
	 * Get hash password
	 *
	 * @param string $v password
	 * @return string hash password
	 */
	public static function getHashPass($v){
		return hash('sha512',sha1($v).$v); 
	}
		
	/**
	 * Get current SID saved in cookie or session
	 *
	 * @return string sid
	 */
	public static function getSidByCookie(){

		$sid = Request::getCookie(Cfg::get('Auth.cookie'));

		if(empty($sid))
			$sid = Request::getSession(Cfg::get('Auth.cookie'));

		return $sid;
	}


	/**
	 * Get current display name (user or email)
	 *
	 * @return string display name
	 */
	public static function getUserDisplay(){
		if(!Auth::logged())return '[User not logged]';
		return Cfg::get('Auth.display') == 0 ? Auth::user() -> username : Auth::user() -> email;
	}

}

?>