<?php

namespace Admin\Controller;

use CoreWine\Request;
use CoreWine\Route;
use CoreWine\Flash;
use CoreWine\Cfg;

use Auth\Controller\AuthController as AuthController;

use Auth\Service\Auth;
use Auth\Repository\AuthRepository;

class AuthController extends AuthController{

	/**
	 * Routes
	 */
	public function __routes(){
		$this -> route('/admin/login',['as' => 'admin/login','__controller' => 'loginView']);
	}
	
	/**
	 * Route to login
	 */
	public function loginView(){
		return $this -> view('Admin/auth/login');
	}


}

?>