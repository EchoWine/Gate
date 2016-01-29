<div id='content'>
	
	<?php foreach(TemplateEngine::getInclude("/Item/admin/main.cat") as $k) include $k; ?>

	<form method='POST'></form>
	<form method='POST' enctype='multipart/form-data'>
		<div class='content-header'>
		
			<?php $actionPage = 'add'; ?><?php foreach(TemplateEngine::getInclude("/Item/admin/main.title") as $k) include $k; ?>

			<?php foreach(TemplateEngine::getInclude("/Item/admin/button-backToList") as $k) include $k; ?>

			<button type='submit' class='button' name='<?php echo $item -> getDataName('action'); ?>' value='<?php echo $item -> getDataOption('action','add'); ?>'>
				<div class='button a success'>
					<span class='fa fa-plus-circle'></span> <span class='button-label'>Add</span>
				</div>
			</button>
		</div>


		<?php foreach(TemplateEngine::getInclude("/Item/admin/response") as $k) include $k; ?>
		<div class='data-form'>
			<!--
			<fieldset>
				<legend> Generale </legend>
			-->
				<?php foreach((array)$item -> getFieldsAdd() as $field){ ?>
					<div class='field'>
						<label><?php echo $field -> label; ?>: </label>
						<div class='data'>
							<?php $name = $field -> getFormName();$value = $field -> printInputValue($item -> results -> record); ?><?php foreach(TemplateEngine::getInclude("/Item/".$field -> getPathInputData()."") as $k) include $k; ?>
						</div>
					</div>
				<?php } ?>

			<!--
			</fieldset>
			-->
		</div>
	</form>
</div>