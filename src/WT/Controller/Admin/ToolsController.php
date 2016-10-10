<?php

namespace WT\Controller\Admin;

use CoreWine\Http\Controller as BasicController;


class ToolsController extends BasicController{


	/**
	 * Set all Routers
	 */
	public function __routes(){

		$this -> route('index') -> url("/admin/tools") -> as("admin/tools") -> middleware('Admin\Middleware\Authenticate');

	}

	/**
	 * @Route
	 */
	public function index(){
		return $this -> view('WT/admin/tools',[]);
	}
}

?>