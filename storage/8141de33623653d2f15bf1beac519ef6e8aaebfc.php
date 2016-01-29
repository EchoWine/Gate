<div id='content'>
	
	<?php foreach(TemplateEngine::getInclude("/Item/admin/main.cat") as $k) include $k; ?>
	
	<div class='content-header'>

		<?php $actionPage = 'view'; ?><?php foreach(TemplateEngine::getInclude("/Item/admin/main.title") as $k) include $k; ?>

		
		<?php foreach(TemplateEngine::getInclude("/Item/admin/button-backToList") as $k) include $k; ?>

		<form method='POST' action='<?php echo $item -> getUrlPageList(); ?>'>
			<input type='hidden' name="<?php echo $item -> getDataName('p_primary'); ?>" value='<?php echo $item -> results -> record[$item -> getFieldPrimary() -> getColumnName()]; ?>'>
			<button type='submit' class='button' name='<?php echo $item -> getDataName('action'); ?>' value='<?php echo $item -> getDataOption('action','delete_s'); ?>'>
				<div class='button a danger'>
					<i class='fa fa-trash'></i> <span class='button-label'>Delete</span>
				</div>
			</button>
		</form>
	</div>

	<?php foreach(TemplateEngine::getInclude("/Item/admin/response") as $k) include $k; ?>
	<div class='data-form'>
		<!--
		<fieldset>
			<legend> Generale </legend>

		-->

			<?php foreach((array)$item -> getFieldsView() as $field){ ?>
				<div class='field'>
					<label><?php echo $field -> label; ?>: </label>
					<div class='data value'>
						<?php echo $field -> printValue($item -> results -> record); ?>
					</div>
				</div>
			<?php } ?>

		<!--
		</fieldset>

		-->
	</div>
</div>