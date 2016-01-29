<?php
	Route::get("/",['as' => 'index','callback' => function(){
		return TemplateEngine::html('admin');
	}]);
?>