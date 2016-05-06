<?php

namespace Admin\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use Item\Controller;

abstract class AdminController extends Controller{

	const PREFIX_URL = 'admin/';
	const PREFIX_ROUTE = 'admin_';

	/**
	 * Name of obj in url
	 */
	public $url;


	public function __routes(){

		$page = $this -> url;
		$this -> route("/".AdminController::PREFIX_URL."{$page}",[
			'as' => AdminController::PREFIX_ROUTE.$page.'_all',
			'__controller' => 'all'
		]);

		$this -> route("/".AdminController::PREFIX_URL."api/{$page}",[
			'as' => AdminController::PREFIX_ROUTE.'api_'.$page.'_all',
			'__controller' => 'api_all'
		]);

	}


	public function all(){

		$results = $this -> __all(AdminController::RESULT_OBJECT);
		
		return $this -> view('Admin/admin/item/all',[
			'results' => $results,
		]);
	}
	
	public function api_all(){

		$results = $this -> __all(AdminController::RESULT_ARRAY);
		return $this -> json($results);
	}
}

?>