<?php

/**
 * Libreria che controlla se tutte le dipendenze sono rispettate
 */
class dependence {	

	public static $dep = [
		'php_version' => 5.5,
		'function' => ['fopen'],
		'extension' => ['imap', 'openssl', 'pdo', 'pdo_mysql'],
		'apache_module' => ['mod_rewrite']
	];

	public static function load(){
		self::check();
	}
	
	public static function check(){

		foreach(self::$dep as $type => $value){
			switch($type){
				case 'php_version':
					if(!version_compare(PHP_VERSION, $value, '>'))
						$error[] = "This CMS require PHP $value or above. ";
					
				break;
				case 'function':
					foreach($value as $name)
						if(!function_exists($name)) 
							$error[] = "PHP Function $name is not available. ";
									
					
				break;
				case 'extension':
					foreach($value as $name)
						if(!extension_loaded($name))
							$error[] = "PHP Extension $name is not loaded. ";
					
				break;
				case 'apache_module':
					foreach($value as $name)
						if(!in_array($name, apache_get_modules()))
							$error[] = "Apache Module $name is not loaded. ";
						
					
				break;
			}
		}
		
		if(!empty($error)){
			foreach($error as $string)
				echo "<b>An error occurred.</b> $string <br>";
			
			die();
		}		
	}
}
