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

		Route::get("/{$page}",['as' => $page.'_all','callback' => 'all']);
	}

	public static function all(){
		
		parent::all();
	}
}

?>