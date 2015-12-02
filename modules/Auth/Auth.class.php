<?php

class Auth{
	
	public static function load(){
	}

	public static function template(){

		$path = dirname(__FILE__)."/templates";

		# If not logged
		if(true){
			TemplateEngine::overwrite('body',$path,'auth.login');

			TemplateEngine::aggregate('style',$path,'auth.style');
		}

	}


}

?>