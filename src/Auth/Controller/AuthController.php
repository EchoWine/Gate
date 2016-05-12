<?php

namespace Auth\Controller;

use CoreWine\Request;
use CoreWine\Router;
use CoreWine\Flash;
use CoreWine\Cfg;

use CoreWine\SourceManager\Controller as Controller;

use Auth\Service\Auth;
use Auth\Repository\AuthRepository;

abstract class AuthController extends Controller{

	/**
	 * Routers
	 */
	public function __routes(){
		$this -> route('loginView')
		-> url('/login')
		-> as('login');
	}

	/**
	 * Check
	 */
	public function __check(){

		Auth::ini();

		$this -> checkAttemptLogout();
		$this -> checkAttemptLogin();
	}

	/**
	 * Router to login
	 */
	public function loginView(){
		return $this -> view('Auth/login');
	}

	/**
	 * Check attempt login
	 */
	public function checkAttemptLogin(){
		if(!Auth::logged() && Request::post('login') !== null){
			$this -> checkLogin();
			Request::refresh();

		}
	}

	/**
	 * Check login
	 */
	public function checkLogin(){

		$user = Request::post('user');
		$pass = Request::post('pass');
		$type = Request::post('remember') !== null;
		$password = Auth::getHashPass($pass);
		$users = AuthRepository::getUsersByRaw($user,$password);

		$type = $type == 1 ? Cfg::get('Auth.remember') : Cfg::get('Auth.normal');

		if(($users_num = count($users)) > 1){
			Flash::add('error','Unable to determine a single user with this data');	

		}else if($users_num == 1){

			Auth::login($users[0],$type);
			
		}else{

			if(Cfg::get('Auth.ambiguous')){
				Flash::add('error','The data entered is incorrect');
			}else{

				if($q['user'] !== $user)
					Flash::add('error','Wrong username/email');
				

				if($q['pass'] !== $pass)
					Flash::add('error','Wrong password');
				

			}


		}
			
	}

	/**
	 * Check attempt logout
	 */
	public function checkAttemptLogout(){
		if(Auth::logged() && Request::post('logout') !== null){
			Auth::logout();
			Request::refresh();
		}
	}


}

?>