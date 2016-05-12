<?php

namespace Basic\Controller;

use CoreWine\Router;
use CoreWine\Request;
use Admin\Controller\AdminController;
use Auth\Service\Auth;

use CoreWine\SourceManager\Controller as Controller;

class DashboardController extends Controller{

	/*
	 * Routers
	 */
	public function __routes(){
		$this -> route('indexRouter')
		-> url('/admin')
		-> as('admin/dashboard');
	}

	/**
	 * Check
	 */
	public function __check(){
		parent::__check();

		# Redirect to /login if user isn't logged
		if(Router::is('admin/login')){
			if(Auth::logged()){
				Request::redirect(Router::url('admin/dashboard'));
			}
		}

		
		# Redirect to /login if user isn't logged
		if(Router::is('admin/dashboard')){
			if(!Auth::logged()){
				Request::redirect(Router::url('admin/login'));
			}
		}

	}
	
	/**
	 * Router to login
	 */
	public function indexRouter(){
		return $this -> view('Admin/admin/dashboard');
	}
}

?>