<?php

include dirname(__FILE__)."/Auth.class.php";

include dirname(__FILE__)."/AuthController.class.php";
include dirname(__FILE__)."/AuthModel.class.php";
include dirname(__FILE__)."/AuthView.class.php";

$AuthModel = new AuthModel();
$AuthController = new AuthController($AuthModel,array(
	'user' => 'auth_username',
	'pass' => 'auth_password'
));

$AuthView = new AuthView($AuthModel,$AuthController);

?>