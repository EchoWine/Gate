<?php

namespace Admin\Controller;

use CoreWine\DB as DB;
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

		Route::get("/{$page}/edit/{primary}",['as' => $page.'_all','callback' => 'all']);
	}

	public function __check(){}

	public function all(){
		
		parent::all();
	}
}

?>