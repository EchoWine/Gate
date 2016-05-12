<?php

namespace SystemInfo\Controller;

use SystemInfo\Model\SystemInfo;
use CoreWine\DataBase\DB;
use CoreWine\Router;

use CoreWine\SourceManager\Controller as Controller;


class SystemInfoController extends Controller{
	

	/**
	 * Routers
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