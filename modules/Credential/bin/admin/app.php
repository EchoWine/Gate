<?php
	
	use Item\Credential as Item;

	# Initialization	
	$Model = new Item\Model();

	$Model -> setFields([
		new ID('id'),
		new Username('user'),
		new Password('pass'),
		new Mail('mail')
	]);

	$Model -> setFieldPrimary('id');

	$Model -> setFieldLabel('user');

	$Controller = new Item\Controller($Model);

	$View = new Item\View($Model,$Controller);


	if($View -> setPage($pageValue)){
		$item = $Controller;

		$item -> updatePathTemplate('admin');
	}

	$View -> setNav(30);

	$Credential = (object)[
		'nav' => (object)[
			'label' => $Controller -> getLabel(),
			'url' => 'index.php'.$Controller -> getUrlMainPage(),
		]
	];

?>