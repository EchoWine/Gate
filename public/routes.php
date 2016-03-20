<?php
	Route::get("/",['as' => 'index','callback' => function(){
		return view('admin');
	}]);
?>