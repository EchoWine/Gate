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
		$class = __NAMESPACE__.$class;
		$filename = str_replace("\\","/",$class);

		if(file_exists($file = PATH_APP.'/'.$filename.".php"))
			return loadClass($file,$class);
		

		if(file_exists($file = PATH_SRC.'/'.$filename.".php"))
			return loadClass($file,$class);

		
		if(file_exists($file = PATH_LIB.'/'.$filename.".php"))
			return loadClass($file,$class);
		
	}

	spl_autoload_register(__NAMESPACE__ . "\\loaderClass");

	

?>