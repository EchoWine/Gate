<?php
	
	use Item\Credential as Item;

	# Initialization	
	$Model = new Item\Model();
	$Model -> setAuth($auth);
	$Model -> ini();

	$Controller = new Item\Controller($Model);

	$View = new Item\View($Model,$Controller);


	/*
	Route::get('/{page}',['as' => 'item','callback' => function($page) use($Controller,$View){
		$View -> setPage();
		return Route::view('index',['item' => $Controller]);
	},'where' => ['page' => Item\Model::getAllObjName()]]);
	*/

	Route::get('/{page}/{action?}/{primary?}',['as' => 'item','callback' => function($page,$action = null,$primary = null) use($Controller,$View){
		$View -> setPage($action,$primary);
		return Route::view('index',['item' => $Controller]);
	},'where' => ['page' => Item\Model::getAllObjName()]]);


	$View -> setNav(30);

	$Credential = (object)[
		'nav' => (object)[
			'label' => $Controller -> getLabel(),
			'url' => $Controller -> getUrlMainPage(),
		]
	];

?>