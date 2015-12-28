<?php
	
	

	# Initialization	
	$Model = new CredentialModel();

	$Model -> setFields([
		new ID('id'),
		new Username('user'),
		new Password('pass'),
		new Mail('mail')
	]);

	$Model -> setPrimary('id');

	$Controller = new CredentialController($Model);

	$View = new CredentialView($Model,$Controller);

	$Controller -> setNamePage('credential');

	$page_obj = 'credential';
	$label = 'Credential';

	$View -> setNav();
	$Controller -> ini();

	$item = $Controller;

	$results = $Controller -> getResults();


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