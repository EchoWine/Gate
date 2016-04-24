<?php

namespace Admin\Controller;

use CoreWine\Route;
use Admin\Controller\AdminController;

use CoreWine\SourceManager\Controller as Controller;

class DashboardController extends Controller{

	/*
	 * Routes
	 */
	public function __routes(){
		Route::get('/admin',['as' => 'admin/dashboard','callback' => 'indexRoute']);
	}

	
	/**
	 * Route to login
	 */
	public static function indexRoute(){
		return view('Admin/admin/dashboard');
	}
}

?>