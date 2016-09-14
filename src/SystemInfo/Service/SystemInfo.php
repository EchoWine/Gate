<?php

namespace SystemInfo\Service;

use CoreWine\DataBase\DB;
use Service;

class SystemInfo implements Service{
	
	public static function load(){

	}
	
	public static function facade(){
		return 'SystemInfo';
	}
	
	public static function getInfoDB(){
		return DB::getServerInfo();
	}

	public static function getInfoOS(){
		return php_uname();
	}

	public static function getInfoServer(){
		return apache_get_version();
	}

	public static function getInfoPHP(){
		return phpversion();
	}

}

?>