<?php

# Definition of some variables

$p = dirname(__FILE__);

Auth::tmplForceLogin($p);


$pathModuleAuth = ModuleManager::getPath()."/Auth/bin/admin/templates/".TemplateEngine::getName()."/";


?>