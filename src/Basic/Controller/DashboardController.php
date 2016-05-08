<?php

namespace Basic\Controller;

use CoreWine\Route;
use CoreWine\Request;
use Admin\Controller\AdminController;
use Auth\Service\Auth;

use CoreWine\SourceManager\Controller as Controller;

class DashboardController extends Controller{

	/*
	 * Routes
	 */
	public function __routes(){
		$this -> route('/admin',['as' => 'admin/dashboard','__controller' => 'indexRoute']);
	}

	/**
	 * Check
	 */
	public function __check(){
		parent::__check();

		# Redirect to /login if user isn't logged
		if(Route::is('admin/login')){
			if(Auth::logged())
				Request::redirect(Route::url('admin/dashboard'));
		}

		
		# Redirect to /login if user isn't logged
		if(Route::is('admin/dashboard')){
			if(!Auth::logged())
				Request::redirect(Route::url('admin/login'));
		}

	}
	
	/**
	 * Route to login
	 */
	public function indexRoute(){
		return $this -> view('Admin/admin/dashboard');
	}
}

?>