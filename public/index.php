<?php
	
	# php.ini
	error_reporting(-1);
	ini_set('display_errors', 'On');

	# Path
	define('PATH_BASE','/gate-cms');
	define('PATH',__DIR__);
	define('PATH_APP','../app');
	define('PATH_SRC','../src');
	define('PATH_LIB','../lib');
	define('PATH_PUBLIC','');
	define('PATH_STORAGE','../storage');
	define('PATH_CONFIG','../config');
	
	
	include '../app/kernel.php';

?>