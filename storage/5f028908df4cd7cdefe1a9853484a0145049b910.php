<div id='container-nav'>
	<div id='nav'>
		<div id='status'>
			<button class='button s cl_st'><span class='fa fa-list'></span></button>
		</div>
		<ul>
			<?php foreach(TemplateEngine::getInclude("admin/nav") as $k) include $k; ?>
		</ul>
	</div>
</div>