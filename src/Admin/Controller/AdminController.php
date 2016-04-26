<?php

namespace Admin\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Route as Route;
use CoreWine\Request as Request;

use Item\Controller\ItemController;

abstract class AdminController extends ItemController{

	/**
	 * Name of obj in url
	 */
	public $url;


	public function __routes(){

		$page = $this -> url;

		$this -> route("/{$page}",['as' => $page.'_all','__controller' => 'all']);
	}


	public function all(){

		$this -> schema = $this -> __schema;
		$results = [];
		$q = DB::table('user') -> get();

		return $this -> view('Admin/admin/item/all',[
			'results' => $results,
		]);
	}
	
	public function resultsToObj(){

	}
}

?>