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
	}


	public function all(){

		$results = $this -> __all();

		print_r($results);

		#OH Yes man
		echo $results[0] -> email -> value;

		return $this -> view('Admin/admin/item/all',[
			'results' => $results,
		]);
	}
	
	public function resultsToObj(){

	}
}

?>