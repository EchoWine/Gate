<?php
	
	

	# Initialization	
	$Model = new Credential();

	$Model -> setFields([
		new ID('id'),
		new Username('user'),
		new Password('pass'),
		new Mail('mail')
	]);

	$Model -> setFieldPrimary('id');

	$Model -> setFieldLabel('user');

	$item = new CredentialController($Model);

	$View = new CredentialView($Model,$item);

	$item -> updatePathTemplate('admin');
	$View -> setPage($pageValue);

	# Add left menu
	$View -> setNav();

	$Credential = (object)[
		'nav' => (object)[
			'label' => $item -> getLabel(),
			'url' => 'index.php'.$item -> getUrlMainPage(),
		]
	];

?>