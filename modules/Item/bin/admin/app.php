<?php

	$dir = dirname(__FILE__);
	Item::$cfg = include($dir."/_config.php");

	$pathModuleItem = ModuleManager::getPath()."/Item/bin/admin/templates/".TemplateEngine::getName()."/";

	TemplateEngine::compile(
		$dir."/templates/".TemplateEngine::getName()."/field/",
		Field::$template
	);

	
?>