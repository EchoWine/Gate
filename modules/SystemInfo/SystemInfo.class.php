<?php

class SystemInfo extends Module{
	

	public static function getDatabaseInfo(){
		return DB::getServerInfo();
	}

	public static function getOSInfo(){
		return php_uname();
	}

	public static function getServerInfo(){
		return apache_get_version();
	}

	public static function getPHPInfo(){
		return phpversion();
	}

}

?>