<?php
	
	use CoreWine\Route as Route;
	
	Route::get("/",['as' => 'index','callback' => function(){
		return view('layout-admin');
	}]);
?>