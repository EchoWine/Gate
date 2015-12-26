<?php
class http{
	
	public static function refresh(){
		header("Location:".$_SERVER['REQUEST_URI']);
	}

	public static function redirect($url){
		header("Location:".$url);
	}
}
?>