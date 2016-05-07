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


	/**
	 * Set all routes
	 */
	public function __routes(){

		$page = $this -> url;
		$this -> route("/".AdminController::PREFIX_URL."{$page}",[
			'as' => AdminController::PREFIX_ROUTE.$page.'_index',
			'__controller' => 'index'
		]);

		$api = $this -> getApiURL();

		$this -> route($api -> get,[
			'as' => AdminController::PREFIX_ROUTE.$page.'_all',
			'__controller' => 'all'
		]);

		$this -> route($api -> add,[
			'as' => AdminController::PREFIX_ROUTE.$page.'_add',
			'__controller' => 'add'
		]);


	}

	/**
	 * Index
	 */
	public function index(){

		return $this -> view('Admin/admin/item/all',[
			'api' => $this -> getApiURL(false),
		]);
	}

	/**
	 * Get api url
	 */
	public function getApiURL($base = true){

		$base = $base ? "/".AdminController::PREFIX_URL : '';

		return (object)[
			'get' =>  $base."api/".$this -> url,
			'edit' => $base."api/".$this -> url.'/edit',
			'add' =>  $base."api/".$this -> url.'/add'
		];
	}
	
	/**
	 * Get all the result
	 */
	public function all(){

		$results = $this -> __all(AdminController::RESULT_ARRAY);
		return $this -> json($results);
	}

	/**
	 * Add new record
	 */
	public function add(){
		$result = $this -> __add();

		if($result){
			$response = (object)['result' => 'success'];
		}else{
			$response = (object)['result' => 'error'];
		}

		return $this -> json($response);
	}

}
?>