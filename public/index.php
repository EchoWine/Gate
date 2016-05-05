<?php
	
	include 'inc.php';

	use CoreWine\Route as Route;

	$inc = Route::load();

	if(empty($inc)){
		die("Current route doens't have a view");
	}

	include $inc;
?>