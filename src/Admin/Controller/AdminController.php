<?php

namespace Admin\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;
use Auth\Service\Auth;

use Item\Controller;

abstract class AdminController extends Controller{

	const PREFIX_URL = 'admin/';
	const PREFIX_ROUTE = 'admin_';


	/**
	 * Set all routes
	 */
	public function __routes(){

		parent::__routes();

		$page = $this -> url;
		$this -> route("/".AdminController::PREFIX_URL."{$page}",[
			'as' => AdminController::PREFIX_ROUTE."_".$page.'_index',
			'__controller' => 'index'
		]);

	}


	/**
	 * Check
	 */
	public function __check(){
		parent::__check();

		# Redirect to /login if user isn't logged
		if(Route::is(AdminController::PREFIX_ROUTE."_".$this -> url.'_index')){
			if(!Auth::logged())
				Request::redirect(Route::url('/admin/login'));
		}

	}
	/**
	 * Index
	 */
	public function index(){

		return $this -> view('Admin/admin/item/all',[
			'api' => $this -> getFullApiURL(),
		]);
	}
	
}
?>