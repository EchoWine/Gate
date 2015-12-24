<?php

	$dir = dirname(__FILE__);
	ItemModel::$config = $dir."/_config.php";

	$pathModuleItem = ModuleManager::getPath()."/Item/bin/admin/templates/".TemplateEngine::getName()."/";

?>