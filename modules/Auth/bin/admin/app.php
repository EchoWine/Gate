<?php

	# Definition of some variables

	$p = dirname(__FILE__);

	AuthView::forceLogin($p);


	$pathModuleAuth = ModuleManager::getPath()."/Auth/bin/admin/templates/".TemplateEngine::getName()."/";
	$logged = true;
?>