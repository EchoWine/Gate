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
	 * Route to login
	 */
	public function loginView(){
		return $this -> view('Admin/auth/login');
	}



}

?>