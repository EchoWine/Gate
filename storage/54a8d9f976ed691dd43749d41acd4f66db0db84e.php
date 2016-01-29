<div class='data-page'>
	Pagina:


	<form method='GET'>
		<?php if($item -> getDataOption('page','actual') > 1){ ?>
		<button type='submit' name='<?php echo $item -> getDataName('page'); ?>' value='<?php echo $item -> getDataOption('page','prev'); ?>' class='button n'>
				<span class='fa fa-angle-left'></span>
			</button>
		<?php }else{ ?>
			<span class='tag n'>
				<span class='fa fa-angle-left'></span>
			</span>
		<?php } ?>
	</form>
	<form method='GET'>
		<input type='text' name='<?php echo $item -> getDataName('page'); ?>' value='<?php echo $item -> getDataOption('page','actual'); ?>' class='input n' >
	</form>
	<form method='GET'>
		<?php if($item -> getDataOption('page','actual') < $item -> results -> pages){ ?>
			<button type='submit' name='<?php echo $item -> getDataName('page'); ?>' value='<?php echo $item -> getDataOption('page','next'); ?>' class='button n'>
				<span class='fa fa-angle-right'></span>
			</button>
		<?php }else{ ?>
			<span class='tag n'>
				<span class='fa fa-angle-right'></span>
			</span>
		<?php } ?>
	</form> of <?php echo $item -> results -> pages; ?> 
	<span class='data-page-separator'></span> 
	<form method='POST'> Show:
		<select name='<?php echo $item -> getDataName('p_result_page'); ?>' id='n_results' class='select n' onchange='this.parentNode.submit()'>
			<?php foreach((array)$item -> getDataAllOption('p_result_page') as $x){ ?>
				<option <?php if($item -> getResultPerPage() == $x){ ?>selected<?php } ?> value='<?php echo $x; ?>'><?php echo $x; ?></option>
			<?php } ?>
		</select> results
	</form>
	<span class='data-page-separator'></span>
	<span>There are <?php echo $item -> results -> count; ?> results</span>
</div>