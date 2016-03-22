<?php
		
	# Path
	define('PATH',dirname(__FILE__));
	define('PATH_MODULE','../modules');
	define('PATH_LIB','../lib');
	define('PATH_CONFIG','../config');
	define('PATH_VIEWS','../views');
	define('PATH_STORAGE','../storage');

	include PATH_LIB."/dependence.class.php";
	dependence::ini();

	include PATH_LIB."/main.fun.php";
	include PATH_LIB."/stdObject.class.php";
	include PATH_LIB."/stdResponse.class.php";
	include PATH_LIB."/http.class.php";
	
	include PATH_LIB."/stdData/main.php";

	include PATH_LIB."/TemplateEngine/main.php";
	include PATH_LIB."/ModuleManager/main.php";
	include PATH_LIB."/Data/main.php";

	include PATH_LIB."/core/main.php";
	
	DB::connect(include PATH_CONFIG.'/database.php');

	# Load all modules
	# ModuleManager::loadAll(PATH_MODULE);

	
	# Modules
	ModuleManager::load(PATH_MODULE."/SystemInfo");
	ModuleManager::load(PATH_MODULE."/Auth");
	ModuleManager::load(PATH_MODULE."/Item");
	ModuleManager::load(PATH_MODULE."/Credential");
	

	# Load template
	TemplateEngine::ini(PATH_STORAGE);

	define('path',Request::getDirUrl());


	# Include template page of modules
	foreach(ModuleManager::loadTemplate() as $k){
		include $k -> app;
		TemplateEngine::compile(
			dirname($k -> app)."/views",
			$k -> name
		);
	}
	

	# Compile
	TemplateEngine::compile(PATH_VIEWS);

?>