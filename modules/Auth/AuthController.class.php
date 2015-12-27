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
	 * Construct
	 * @param $model (object) model
	 * @param $cfg (array) set of config
	 */
	public function __construct($model,$cfg){
		$this -> model = $model;
		$this -> cfg = $cfg['data'];
		unset($cfg['data']);
		$this -> model -> cfg = $cfg;
	}

	/**
	 * Check all the interaction with user
	 * @return (array) response of interaction
	 */
	public function check(){
		$this -> model -> alterTable();
		$this -> updateData();

		$r = [];

		$this -> model -> cleanSession();
		$this -> info = $this -> model -> checkSession();
		$this -> logged = !empty($this -> info);

		# Check for logout
		if($this -> logged && $this -> data['logout'] -> value !== null)
			$this -> model -> checkAttemptLogout();

		# Check for login
		if(!$this -> logged && $this -> data['login'] -> value !== null)
			$r[] = $this -> model -> checkAttemptLogin(
				$this -> data['user'] -> value,
				$this -> data['pass'] -> value,
				$this -> data['remember'] -> value !== null
			);


		return $r;
	}

	/**
	 * Retrieve all data sent by user
	 * @return (array) data
	 */
	public function retrieveData(){
		return [

			# User: Username or Email (depends on config)
			'user' => new stdDataPost('User',$this -> cfg['post_user']),

			# Password
			'pass' => new stdDataPost('Password',$this -> cfg['post_pass']),

			# Login
			'login' => new stdDataPost('Login',$this -> cfg['post_login']),

			# Logout
			'logout' => new stdDataPost('Logout',$this -> cfg['post_logout']),

			# Remember me
			'remember' => new stdDataPost('Remember me',$this -> cfg['post_remember'])
				
		];
	}

	/**
	 * Get current info about user
	 * @return (object) info
	 */
	public function getUserInfo(){
		return $this -> info;
	}

	/**
	 * Get current display name (user or email)
	 * @return (string) display name
	 */
	public function getUserDisplay(){
		return !empty($this -> info) ? $this -> model -> getUserDisplay($this -> info) : '';
	}
}

?>