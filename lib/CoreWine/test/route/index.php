<?php

include "../../main.php";

use CoreWine\Route as Route;

$x = "Global variable";

Route::get("/",function(){
	echo 'Index';
});

Route::get("/foo",function(){
	echo 'This is foo';
});

Route::get("/fee",['as' => 'fee','callback' => function(){
	echo 'This is fee';
}]);

Route::get("/var/{ban}",function($ban){
	echo "This is $ban";
});

Route::get("/hoo/{v?}",['as' => 'hoo','callback' => function($hoo_var = 'default'){
	Route::view(['hoo_var' => $hoo_var]);
	return 'hoo.php';	
}]);

Route::get("/ste/{v}",['as' => 'ste','callback' => function($var = 'default'){
	Route::view(['hoo_var' => $var]);	
	return 'hoo.php';
}]);

Route::get("/ste/{v}/{a}/{d}",['as' => 'sta','callback' => function($v,$a,$d){
	Route::view(['hoo_var' => $v*$a*$d]);	
	return 'hoo.php';
}]);

Route::get("/cha/{d}",['as' => 'cha','callback' => function($d){
	Route::view(['hoo_var' => 'BINGO 10!']);	
	return 'hoo.php';
},'where' => ['d' => '10']]);

Route::add("cha",['hoo_var' => 'OVERWRITE BINGO 10!']);

Route::get("/num/{d?}",['as' => 'num','callback' => function($d = 0){
	Route::view(['hoo_var' => 'BINGO '.$d]);
	return 'hoo.php';
},'where' => ['d' => '[0-9]+']]);


if(($c = Route::load()) !== null)
	include $c;

?>
<br><br>Link<br>
<a href='<?=dirname($_SERVER['SCRIPT_NAME']).'/foo';?>'>Basic</a><br>
<a href='<?=dirname($_SERVER['SCRIPT_NAME']).'/var/200';?>'>Basic with var</a><br>
<a href='<?=Route::is('fee');?>'>Alias</a><br>
<a href='<?=Route::is('hoo');?>'>Alias with optional var (no var sent)</a><br>
<a href='<?=Route::is('hoo','Sexy');?>'>Alias with optional var (var sent)</a><br>
<a href='<?=Route::is('ste','necessary');?>'>Alias with required var (var sent)</a><br>
<a href='<?=Route::is('sta',2,2,2);?>'>Alias with 3 required var (var sent)</a><br>
<a href='<?=Route::is('cha',10);?>'>Alias with 1 required var and where 10 with overwrite var</a><br>
<a href='<?=Route::is('cha',15);?>'>Alias with 1 required var and where 15 [No Routes found => Correct] </a><br>
<a href='<?=Route::is('num');?>'>Alias with 1 optional var and where number</a><br>
<a href='<?=Route::is('num',92);?>'>Alias with 1 optional var (sent) and where number</a><br>
<a href='<?=Route::is('num','foo');?>'>Alias with 1 optional var and where string [No Routes found => Correct] </a><br>

<?php
	try{
		Route::is('ste');
	}catch(Exception $e){
		// Correct, error, missing parameter
	}

?>