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

	$View -> setNav();
	$Controller -> ini();

	$item = new stdClass();
	$item -> toAdd = $Controller -> button -> toAdd;
	$item -> toList = $Controller -> button -> toList;

	if($pageValue == $page_obj){

		$Controller -> check();
		$View -> setStyle();

		switch($Controller -> getPageValue()){
			case $Controller -> getPageParamAdd():
				$View -> setPageAdd();
			break;
			default:
				$View -> setPageList();
			break;
		}
	}

	# Menu
	$Credential = (object)[
		'nav' => (object)[
			'label' => $label,
			'url' => 'index.php'.$Controller -> getUrlMainPage(),
		]
	];

?>