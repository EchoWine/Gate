<?php

	$dir = dirname(__FILE__);

	$model = new Auth();

	$auth = new AuthController($model,include $dir."/_config.php");
	$auth -> check();
	$auth -> updatePathTemplate('admin');

	$view = new AuthView($model,$auth);

	# Definition pages
	$view -> setLogin();
	$view -> setHeader();


?>