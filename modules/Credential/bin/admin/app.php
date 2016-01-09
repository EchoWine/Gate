<?php
	
	use Item\Credential as Item;

	# Initialization	
	$Model = new Item\Model();

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