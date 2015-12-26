<?php

class AuthController extends Controller{
	public $model;
	public $logged;
	public $cfg;

	public function __construct($model,$cfg){
		$this -> model = $model;
		$this -> cfg = $cfg['data'];
		unset($cfg['data']);
		$this -> model -> cfg = $cfg;
	}

	public function check(){
		$this -> model -> alterTable();
		$this -> updateData();

		$r = [];

		$this -> model -> cleanSession();
		$this -> logged = $this -> model -> checkSession();

		# Check for logout
		if($this -> logged && $this -> data['logout'] -> value !== null)
			$this -> model -> checkAttemptLogout();

		# Check for login
		if(!$this -> logged && $this -> data['login'] -> value !== null)
			$r[] = $this -> model -> checkAttemptLogin(
				$this -> data['user'] -> value,
				$this -> data['pass'] -> value
			);


		return $r;
	}


	public function retrieveData(){
		return [

			# User: Username or Email (depends on config)
			'user' => new stdDataPost('User',$this -> cfg['post_user']),

			# Password
			'pass' => new stdDataPost('Password',$this -> cfg['post_pass']),

			# Login
			'login' => new stdDataPost('Login',$this -> cfg['post_login']),

			# Logout
			'logout' => new stdDataPost('Logout',$this -> cfg['post_logout'])
				
		];
	}

}

?>