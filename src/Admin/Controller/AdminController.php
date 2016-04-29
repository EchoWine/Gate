<?php

namespace Admin\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use Item\Controller;

abstract class AdminController extends Controller{

	/**
	 * Name of obj in url
	 */
	public $url;


	public function __routes(){

		$page = $this -> url;

		$this -> route("/{$page}",['as' => $page.'_all','__controller' => 'all']);
		$this -> route("/api/{$page}",['as' => 'api_'.$page.'_all','__controller' => 'api_all']);
	}


	public function all(){

		$results = $this -> __all();
		
		return $this -> view('Admin/admin/item/all',[
			'results' => $results,
		]);
	}
	
	public function api_all(){

		$results = $this -> __all();
		return $this -> json($results);
	}
}

?>