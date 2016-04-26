<?php
		
	function loadClass($file,$class){

		require_once $file;

		if(is_subclass_of($class,"CoreWine\Service")){
			$name = explode('\\',$class);
			$name = end($name);
			class_alias($class, $name);
  		}


		return;
	}

  	function loaderClass($class){

  		if(file_exists($file = PATH_APP.'/'.__NAMESPACE__.$class.".php"))
  			return loadClass($file,$class);
  		

  		if(file_exists($file = PATH_SRC.'/'.__NAMESPACE__.$class.".php"))
  			return loadClass($file,$class);

  		
  		if(file_exists($file = PATH_LIB.'/'.__NAMESPACE__.$class.".php"))
  			return loadClass($file,$class);
  		
	}

	spl_autoload_register(__NAMESPACE__ . "\\loaderClass");

	

?>