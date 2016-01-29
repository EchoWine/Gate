<div id='content'>
	
	<?php foreach(TemplateEngine::getInclude("/Item/admin/main.cat") as $k) include $k; ?>

	<form method='POST' enctype='multipart/form-data'>
		<div class='content-header'>
			<?php $actionPage = 'edit'; ?><?php foreach(TemplateEngine::getInclude("/Item/admin/main.title") as $k) include $k; ?>

			<input type='hidden' name="<?php echo $item -> getDataName('p_primary'); ?>" value='<?php echo $item -> results -> record[$item -> getFieldPrimary() -> getColumnName()]; ?>'>

			<?php foreach(TemplateEngine::getInclude("/Item/admin/button-backToList") as $k) include $k; ?>

			<button type='submit' class='button' name='<?php echo $item -> getDataName('action'); ?>' value='<?php echo $item -> getDataOption('action','edit'); ?>'>
				<div class='button a warning'>
					<i class='fa fa-plus-circle'></i> <span class='button-label'>Apply changes</span>
				</div>
			</button>
		</div>


		<?php foreach(TemplateEngine::getInclude("/Item/admin/response") as $k) include $k; ?>

		<div class='data-form'>
			<!--
			<fieldset>
				<legend> Generale </legend>

			-->
				<?php if(!empty($item -> results -> primary)){ ?>
					<div class='field'>
						<label>Take from: </label>
						<div class='data'>
							<select id='item-edit-take' class='select n' item-baseurl='<?php echo $item -> getUrlPageEdit(); ?>' item-getname='<?php echo $item -> getDataName('g_primary_m'); ?>'>
								<option value='<?php echo $item -> getDataValue('g_primary'); ?>'>
									<?php echo $item -> results -> record[$item -> getFieldLabel() -> getColumnName()]; ?>
								</option>
								<?php foreach((array)$item -> results -> primary as $k){ ?>
									<option value='<?php echo $k[$item -> getFieldPrimary() -> getColumnName()]; ?>'>
										<?php echo $k[$item -> getFieldLabel() -> getColumnName()]; ?>
									</option>
								<?php } ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<?php foreach((array)$item -> getFieldsEdit() as $field){ ?>
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