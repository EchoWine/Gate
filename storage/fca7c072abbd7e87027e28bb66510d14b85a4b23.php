<?php foreach(TemplateEngine::getInclude("admin/header") as $k) include $k; ?>
<div id='container'>
	<?php foreach(TemplateEngine::getInclude("admin/container-nav") as $k) include $k; ?>
	<?php foreach(TemplateEngine::getInclude("admin/content") as $k) include $k; ?>
</div>