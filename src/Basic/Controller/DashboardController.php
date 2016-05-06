<?php

namespace Basic\Controller;

use CoreWine\Route;
use Admin\Controller\AdminController;

use CoreWine\SourceManager\Controller as Controller;

class DashboardController extends Controller{

	/*
	 * Routes
	 */
	public function __routes(){
		$this -> route('/admin',['as' => 'admin/dashboard','__controller' => 'indexRoute']);
	}

	
	/**
	 * Route to login
	 */
	public function indexRoute(){
		return $this -> view('Admin/admin/dashboard');
	}
}

?>