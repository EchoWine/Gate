<li>
	<span class='inactive header-user-name'>
		<span><?php echo $auth -> getUserDisplay(); ?></span>
	</span>
</li>
<li class='logout'>
	<?php foreach(TemplateEngine::getInclude("/Auth/admin/logout") as $k) include $k; ?>
</li>