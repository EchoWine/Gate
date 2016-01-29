<div id='content'>
	
	<?php foreach(TemplateEngine::getInclude("/Item/admin/main.cat") as $k) include $k; ?>

	<div class='content-header'>
		<?php $actionPage = ''; ?><?php foreach(TemplateEngine::getInclude("/Item/admin/main.title") as $k) include $k; ?>

		
		<?php foreach(TemplateEngine::getInclude("/Item/admin/button-backToList") as $k) include $k; ?>
	</div>
		<?php foreach(TemplateEngine::getInclude("/Item/admin/response") as $k) include $k; ?>
</div>