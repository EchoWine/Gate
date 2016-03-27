<?php

namespace SystemInfo\Controller;

use SystemInfo\Model\SystemInfo;
use CoreWine\DB as DB;
use CoreWine\Route as Route;


class SystemInfoController{
	
	/**
	 * Constructor
	 */
	public function __construct(){
		$this -> setNav();
		$this -> setPage();
	}

	/**
	 * Set navigation
	 */
	public static function setNav(){

		$SystemInfo = [
			'nav' => [
				'label' => 'System Info',
			]
		];

		Route::add('index',['SystemInfo' => $SystemInfo]);

		\Module::TemplateAggregate('admin/nav','admin/nav',99);
	}

	/**
	 * Set routing
	 */
	public static function setPage(){
		Route::get('/system-info',['as' => 'system-info','callback' => function(){

			\Module::TemplateOverwrite('admin/content','admin/page');

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

			return view('admin',['SystemInfo' => $SystemInfo]);
		 }]);


	}

}

?>