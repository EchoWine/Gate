<?php foreach((array)$auth -> response as $response){ ?>
	
	<div class='alert alert-<?php switch($response -> type){ ?>
<?php case 0: ?>danger<?php break; ?>
<?php case 1: ?>success<?php break; ?>
<?php case 2: ?>warning<?php break; ?>
<?php case 3: ?>info<?php break; ?>
<?php } ?>'>

		<strong><?php echo $response -> title; ?></strong>

		<br><br>
		<?php if(!is_array($response -> message)){ ?>
			<?php echo $response -> message; ?>
		<?php }else{ ?>

			<ul>
				<?php foreach((array)$response -> message as $mex){ ?>
					<li><?php echo $mex; ?></li>
				<?php } ?>
			</ul>

		<?php } ?>
	</div>
<?php } ?>