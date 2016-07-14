<?php

namespace SystemInfo\Controller;

use SystemInfo\Service\SystemInfo;
use CoreWine\DataBase\DB;
use CoreWine\Http\Router;

use CoreWine\SourceManager\Controller as Controller;


class SystemInfoController extends Controller{
	

	public function __check(){

		SystemInfo::load();
	}
	/**
	 * Routers
	 */
	public function __routes(){

		$this -> route('index') -> url("/admin/system-info");

	}

	/**
	 * Set index
	 */
	public function index(){


		return $this -> view('SystemInfo/admin/index');

	}

}

?>