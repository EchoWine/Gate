<!DOCTYPE html>
<html>
<?php foreach(TemplateEngine::getInclude("admin/head") as $k) include $k; ?>
<body>
	<?php foreach(TemplateEngine::getInclude("admin/container") as $k) include $k; ?>
	<?php foreach(TemplateEngine::getInclude("admin/script") as $k) include $k; ?>
</body>
</html>