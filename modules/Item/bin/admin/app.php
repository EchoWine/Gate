<?php

	$dir = dirname(__FILE__);
	ItemController::$cfg = include($dir."/_config.php");

	$pathModuleItem = ModuleManager::getPath()."/Item/bin/admin/templates/".TemplateEngine::getName()."/";

?>