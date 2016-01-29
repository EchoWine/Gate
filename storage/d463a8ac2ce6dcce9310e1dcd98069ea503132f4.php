<div id='header'>
	<div id='header-main'>
		S
	</div>
	<form method='POST' id='header-user'>
		<ul>
			<?php foreach(TemplateEngine::getInclude("admin/header-nav") as $k) include $k; ?>

			<li id='status_nav' class='inactive'>
				<button type='button' class='button r cl_st'>
					<span class='fa fa-list'></span>
				</button>
			</li>
		</ul>
	</form>
</div>
