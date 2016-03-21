<?php

	$model = new Auth();

	$auth = new AuthController($model,include __DIR__."/_config.php");
	$auth -> check();

	$view = new AuthView($model,$auth);

	# Definition pages
	$view -> setLogin();
	$view -> setHeader();

	define('path_auth',Request::getDirUrl().'../modules/Auth');


?>