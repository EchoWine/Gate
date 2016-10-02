<?php

namespace WT\Controller\Admin;

use CoreWine\Http\Router;
use CoreWine\Http\Request;
use Auth\Service\Auth;

use CoreWine\Http\Controller as Controller;

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
		return $this -> view('WT/admin/dashboard');
	}
}

?>