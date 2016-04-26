<?php

namespace SystemInfo\Controller;

use SystemInfo\Model\SystemInfo;
use CoreWine\DataBase\DB;
use CoreWine\Route as Route;

use CoreWine\SourceManager\Controller as Controller;


class SystemInfoController extends Controller{
	

	/**
	 * Routes
	 */
	public function __routes(){
		$this -> route('/system-info',['as' => 'system-info','__controller' => 'index']);
	}

	/**
	 * Set index
	 */
	public function index(){

		return $this -> view('SystemInfo/admin/index');

	}

}

?>