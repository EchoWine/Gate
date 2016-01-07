<?php

	$dir = dirname(__FILE__);
	Item::$cfg = include($dir."/_config.php");


	TemplateEngine::compile(
		$dir."/templates/".TemplateEngine::getName()."/field/",
		Field::$template
	);

	
?>