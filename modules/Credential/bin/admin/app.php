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

	# Url item
	$page_obj = 'credential';

	$Controller -> setNameURL($page_obj);

	# Label item
	$label = 'Credential';

	# Initialization Controller
	$Controller -> ini();
	$item = $Controller;


	if($pageValue == $page_obj){

		$View -> setCat();
		$View -> setTitle();
		
		# Check all information (data)
		$Controller -> check();

		# Add style
		$View -> setStyle();

		switch($Controller -> getPageActionValue()){
			case $Controller -> getPageActionAdd():

				# Set current page to Add
				$View -> setPageAdd();
			break;
			default:

				# Ini list
				$Controller -> iniList();

				# Get results for list
				$results = $Controller -> getResults();

				# Set current page to List
				$View -> setPageList();
			break;
		}
	}


	# Add left menu
	$View -> setNav();

	$Credential = (object)[
		'nav' => (object)[
			'label' => $label,
			'url' => 'index.php'.$Controller -> getUrlMainPage(),
		]
	];

?>