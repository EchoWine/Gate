<?php
	
	use CoreWine\DB as DB;
	use CoreWine\Request as Request;
	use CoreWine\Route as Route;

	# Path
	define('PATH',dirname(__FILE__));
	define('PATH_SRC','../src');
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
	include PATH_LIB."/SourceManager/main.php";
	include PATH_LIB."/Data/main.php";

	include PATH_LIB."/core/main.php";
	
	DB::connect(include PATH_CONFIG.'/database.php');

	# Load all sources
	#SourceManager::loadAll(PATH_SRC);

	
	# Sources

	#SourceManager::load(PATH_SRC."/Auth");
	#SourceManager::load(PATH_SRC."/SystemInfo");
	SourceManager::load(PATH_SRC."/Example");
	SourceManager::load(PATH_SRC."/Item");
	SourceManager::load(PATH_SRC."/Admin");
	SourceManager::load(PATH_SRC."/User");
	
	SourceManager::loaded();


	# Alias
	class_alias('CoreWine\Request', 'Request');
	class_alias('CoreWine\Route', 'Route');
	class_alias('CoreWine\Flash', 'Flash');
	//class_alias('Auth\Service\Auth', 'Auth');
	//class_alias('SystemInfo\Service\SystemInfo', 'SystemInfo');

	# Load template
	TemplateEngine::ini(PATH_STORAGE);

	define('path',Request::getDirUrl());


	# Compile
	TemplateEngine::compile(PATH_VIEWS);

	# Include template page of sources
	SourceManager::loadViews();

?>