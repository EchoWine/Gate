<?php
	
	Route::get('/system-info',['as' => 'system-info','callback' => function(){
		SystemInfoView::setPage();
		return Route::callback('index');
	}]);


?>