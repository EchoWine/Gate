<?php

namespace SystemInfo\Controller;

use SystemInfo\Model\SystemInfo;
use CoreWine\DB as DB;
use CoreWine\Route as Route;
use FrameworkWine\Controller as Controller;


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
		Route::add('index',$this -> navAction());
		Route::get('/system-info',['as' => 'system-info','callback' => 'index']);
	}

	/**
	 * Set navigation
	 */
	public function navAction(){

		$SystemInfo = [
			'nav' => [
				'label' => 'System Info',
			]
		];

		return ['SystemInfo' => $SystemInfo];
	}

	/**
	 * Set index
	 */
	public static function index(){

		$SystemInfo = [
			'Server_Label' => 'Server',
			'Server_Info' => SystemInfo::getServerInfo(),
			'PHP_Label' => 'PHP',
			'PHP_Info' => SystemInfo::getPHPInfo(),
			'OS_Label' => 'OS',
			'OS_Info' => SystemInfo::getOSInfo(),
			'DB_Label' => 'DB',
			'DB_Info' => SystemInfo::getDatabaseInfo(),
			'nav' => [
				'label' => 'System Info',
			]
		];

		return static::view('admin/index',['SystemInfo' => $SystemInfo]);

	}

}

?>