<?php

	include __DIR__."/routes.php";

	Item::$cfg = include(__DIR__."/_config.php");

	$item = ItemView::getCurrentObj();

	define('path_item',http::getDirUrl().'../modules/Item/public');
	
?>