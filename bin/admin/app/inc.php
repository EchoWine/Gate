<?php
	
	$path_lib = '../../../lib';
	$path_config = '../config';

	include $path_lib."/database/main.php";
	
	DB::connect(include $path_config.'/database.php');

?>