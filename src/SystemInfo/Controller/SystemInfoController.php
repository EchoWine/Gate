<?php

namespace SystemInfo\Controller;

use SystemInfo\Model\SystemInfo;
use CoreWine\DataBase\DB;
use CoreWine\Route as Route;

use CoreWine\SourceManager\Controller as Controller;


class SystemInfoController extends Controller{
	

	/**
	 * Constructor
	 */
	public function __construct(){
		self::__routes();
	}

	/**
	 * Routes
	 */
	public function __routes(){
		Route::get('/system-info',['as' => 'system-info','callback' => 'index']);
	}

	/**
	 * Set index
	 */
	public static function index(){

		return view('SystemInfo/admin/index');

	}

}

?>