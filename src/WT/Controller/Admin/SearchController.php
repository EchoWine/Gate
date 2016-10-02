<?php

namespace WT\Controller\Admin;

use CoreWine\Http\Controller as BasicController;


class SearchController extends BasicController{


	/**
	 * Set all Routers
	 */
	public function __routes(){

		$this -> route('search') -> url("/admin/search") -> as("admin/search") -> middleware('Admin\Middleware\Authenticate');

	}

	/**
	 * Route search
	 */
	public function search(){
		return $this -> view('WT/admin/search',[]);
	}
}

?>