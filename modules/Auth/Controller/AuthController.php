<?php

namespace Auth\Controller;

use Auth\Model\Auth;
use CoreWine\Request as Request;
use CoreWine\Route as Route;
use FrameworkWine\Controller as Controller;

class AuthController extends Controller{

	/**
	 * Is user logged
	 */
	public $logged;

	/**
	 * Information about current user
	 */
	public $info;

	/**
	 * Config
	 */
	public $cfg;

	/**
	 * Constructor
	 */
	public function __construct(){
		$this -> cfg = include dirname(__FILE__)."/../Resources/config/config.php";
	}

	/**
	 * Response
	 */
	public $response = [];

	/**
	 * Routes
	 */
	public function __routes(){
		Route::global(['auth' => $this,'path_auth' => Request::getDirUrl().'../modules/Auth']);
		Route::get('/login',['as' => 'login','callback' => 'loginAction']);
		$this -> redirectRouteLogin();
	}

	/**
	 * Check
	 */
	public function __check(){
		$this -> redirectRouteLogin();
		$this -> check();
	}

	/**
	 * Redirect to route login
	 */
	public function redirectRouteLogin(){

		if(!Route::is('login') && !$this -> logged)
			Request::redirect(Route::url('login'));
		
	}

	public static function loginAction(){
		return static::view('/login');
	}

	/**
	 * Check all the interaction with user
	 */
	public function check(){
		$this -> model = new Auth($this -> cfg);
		$this -> model -> alterTable();
		$this -> updateData();

		$this -> model -> cleanSession();
		$this -> info = $this -> model -> checkSession();
		$this -> logged = !empty($this -> info);

		$this -> checkAttemptLogout();
		$this -> checkAttemptLogin();
	}

	/**
	 * Check attempt logout
	 */
	public function checkAttemptLogout(){
		if($this -> logged && Request::post('logout') !== null)
			$this -> model -> logout();
	}

	/**
	 * Check attempt login
	 */
	public function checkAttemptLogin(){
		
		if(!$this -> logged && Request::post('login') !== null)
			$this -> response[] =  $this -> model -> login(
				$this -> getData('user') -> value,
				$this -> getData('pass') -> value,
				$this -> getData('remember') -> value !== null
			);

	}

	/**
	 * Retrieve all data sent by user
	 *
	 * @return array data
	 */
	public function retrieveData(){
		$c = $this -> cfg['data'];
		return [

			# User: Username or Email (depends on config)
			'user' => new \stdDataPost($c['post_user'],null,'Username or E-mail'),

			# Password
			'pass' => new \stdDataPost($c['post_pass'],null,'Password'),

			# Login
			'login' => new \stdDataPost($c['post_login'],null,'Login'),

			# Logout
			'logout' => new \stdDataPost($c['post_logout'],null,'Logout'),

			# Remember me
			'remember' => new \stdDataPost($c['post_remember'],null,'Remember me')
				
		];
	}

	/**
	 * Get current info about user
	 *
	 * @return object info
	 */
	public function getUserInfo(){
		return $this -> info;
	}

	/**
	 * Get current display name (user or email)
	 *
	 * @return string display name
	 */
	public function getUserDisplay(){
		return !empty($this -> info) ? $this -> model -> getUserDisplay($this -> info) : '';
	}

}

?>