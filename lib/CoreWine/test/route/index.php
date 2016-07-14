<?php

include "../../main.php";

use CoreWine\Http\Router;

$x = "Global variable";

Router::get("/",function(){
	echo 'Index';
});

Router::get("/foo",function(){
	echo 'This is foo';
});

Router::get("/fee",['as' => 'fee','callback' => function(){
	echo 'This is fee';
}]);

Router::get("/var/{ban}",function($ban){
	echo "This is $ban";
});

Router::get("/hoo/{v?}",['as' => 'hoo','callback' => function($hoo_var = 'default'){
	Router::view(['hoo_var' => $hoo_var]);
	return 'hoo.php';	
}]);

Router::get("/ste/{v}",['as' => 'ste','callback' => function($var = 'default'){
	Router::view(['hoo_var' => $var]);	
	return 'hoo.php';
}]);

Router::get("/ste/{v}/{a}/{d}",['as' => 'sta','callback' => function($v,$a,$d){
	Router::view(['hoo_var' => $v*$a*$d]);	
	return 'hoo.php';
}]);

Router::get("/cha/{d}",['as' => 'cha','callback' => function($d){
	Router::view(['hoo_var' => 'BINGO 10!']);	
	return 'hoo.php';
},'where' => ['d' => '10']]);

Router::add("cha",['hoo_var' => 'OVERWRITE BINGO 10!']);

Router::get("/num/{d?}",['as' => 'num','callback' => function($d = 0){
	Router::view(['hoo_var' => 'BINGO '.$d]);
	return 'hoo.php';
},'where' => ['d' => '[0-9]+']]);


if(($c = Router::load()) !== null)
	include $c;

?>
<br><br>Link<br>
<a href='<?=dirname($_SERVER['SCRIPT_NAME']).'/foo';?>'>Basic</a><br>
<a href='<?=dirname($_SERVER['SCRIPT_NAME']).'/var/200';?>'>Basic with var</a><br>
<a href='<?=Router::is('fee');?>'>Alias</a><br>
<a href='<?=Router::is('hoo');?>'>Alias with optional var (no var sent)</a><br>
<a href='<?=Router::is('hoo','Sexy');?>'>Alias with optional var (var sent)</a><br>
<a href='<?=Router::is('ste','necessary');?>'>Alias with required var (var sent)</a><br>
<a href='<?=Router::is('sta',2,2,2);?>'>Alias with 3 required var (var sent)</a><br>
<a href='<?=Router::is('cha',10);?>'>Alias with 1 required var and where 10 with overwrite var</a><br>
<a href='<?=Router::is('cha',15);?>'>Alias with 1 required var and where 15 [No Routers found => Correct] </a><br>
<a href='<?=Router::is('num');?>'>Alias with 1 optional var and where number</a><br>
<a href='<?=Router::is('num',92);?>'>Alias with 1 optional var (sent) and where number</a><br>
<a href='<?=Router::is('num','foo');?>'>Alias with 1 optional var and where string [No Routers found => Correct] </a><br>

<?php
	try{
		Router::is('ste');
	}catch(Exception $e){
		// Correct, error, missing parameter
	}

?>