<?php

class Auth{
	
	public static function load(){

	}

	public static function tmplForceLogin($path){

		$path = $path."/templates";

		# If not logged
		if(true){
			TemplateEngine::overwrite('body',$path,'auth.login');

			TemplateEngine::aggregate('style',$path,'auth.style');
		}
	}

}

?>