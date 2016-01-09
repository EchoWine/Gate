<?php

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
	 * Response
	 */
	public $response = [];

	/**
	 * Construct
	 *
	 * @param object $model model
	 * @param array $cfg set of config
	 */
	public function __construct($model,$cfg){
		$this -> model = $model;

		$this -> cfg = $cfg;
		$this -> model -> cfg = $cfg;
	}

	/**
	 * Check all the interaction with user
	 *
	 * @return array response of interaction
	 */
	public function check(){
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
		if($this -> logged && $this -> getData('logout') -> value !== null)
			$this -> model -> logout();
	}

	/**
	 * Check attempt login
	 */
	public function checkAttemptLogin(){
		
		if(!$this -> logged && $this -> data['login'] -> value !== null)
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
			'user' => new stdDataPost($c['post_user'],null,'User'),

			# Password
			'pass' => new stdDataPost($c['post_pass'],null,'Password'),

			# Login
			'login' => new stdDataPost($c['post_login'],null,'Login'),

			# Logout
			'logout' => new stdDataPost($c['post_logout'],null,'Logout'),

			# Remember me
			'remember' => new stdDataPost($c['post_remember'],null,'Remember me')
				
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