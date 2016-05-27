<?php

namespace Admin\Controller;

use CoreWine\Request;
use CoreWine\Router;
use CoreWine\Flash;
use CoreWine\Cfg;

use Auth\Service\Auth;
use Auth\Repository\AuthRepository;

class AuthController extends \Auth\Controller\AuthController{

	/**
	 * Routers
	 */
	public function __routes(){
		$this -> route('loginView')
		-> url('/admin/login')
		-> as('admin/login');
	}
	
	/**
	 * Router to login
	 */
	public function loginView(){
		return $this -> view('Admin/auth/login');
	}


}

?>