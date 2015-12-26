<?php
	
	

	# Initialization	
	$Model = new CredentialModel();

	$Model -> setFields([
		new Field\ID('id'),
		new Field\Username('user'),
		new Field\Password('pass'),
		new Field\Mail('mail')
	]);

	$Model -> setPrimary('id');
	

	$Controller = new CredentialController($Model);

	$View = new CredentialView($Model,$Controller);

	$Controller -> setNamePage('credential');

	$page_obj = 'credential';
	$label = 'Credential';

	$pageCredential = $pageValue == $page_obj;


	$View -> setPath($p);
	$View -> setNav();
	$View -> setPage();

	# Menu
	$Credential = (object)[
		'nav' => (object)[
			'label' => $label,
			'url' => 'index.php'.$Controller -> getUrlMainPage(),
		]
	];

?>