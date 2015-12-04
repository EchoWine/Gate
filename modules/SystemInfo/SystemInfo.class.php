<?php

class SystemInfo{
	
	public static function load(){

	}

	public static function template($path){
		TemplateEngine::overwrite('content','SystemInfo.page','$pageSystemInfo');

		$path = $path."/templates";
		TemplateEngine::aggregate('nav',$path,'SystemInfo.nav',99);
	}

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