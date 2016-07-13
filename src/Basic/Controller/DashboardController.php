<?php

namespace Basic\Controller;

use CoreWine\Router;
use CoreWine\Request;
use Admin\Controller\AdminController;
use Auth\Service\Auth;

use CoreWine\SourceManager\Controller as Controller;

class DashboardController extends Controller{


	/**
	 * Middleware
	 *
	 * @var Array
	 */
	public $middleware = ['Admin\Middleware\Authenticate'];

	/**
	 * Routers
	 */
	public function __routes(){
		$this -> route('indexRouter')
		-> url('/admin')
		-> as('admin/dashboard');
	}
	
	/**
	 * Router to login
	 */
	public function indexRouter(){
		return $this -> view('Admin/admin/dashboard');
	}
}

?>