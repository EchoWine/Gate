<!DOCTYPE html>
<html>
<?php foreach(TemplateEngine::getInclude("admin/head") as $k) include $k; ?>
<?php foreach(TemplateEngine::getInclude("/Auth/admin/style") as $k) include $k; ?>

<body>
	<div id='container-auth'>

		<h1>Sign in</h1>
		<br>
		<form method='POST'>
			<div>
				<div>
					<div>
						<input type='text' name='<?php echo $auth -> getData('user') -> name; ?>' value='<?php echo $auth -> getData('user') -> value; ?>' class='input d' placeholder='<?php echo $auth -> getData('user') -> label; ?>' required autofocus autocomplete='off'>
					</div>
					<div>
						<input type='password' name='<?php echo $auth -> getData('pass') -> name; ?>' class='input d' placeholder='<?php echo $auth -> getData('pass') -> label; ?>' required autocomplete='off'>
					</div>
				
				</div>
				<div id='bar-sub'>
					<input type='submit' name='<?php echo $auth -> getData('login') -> name; ?>' value='<?php echo $auth -> getData('login') -> label; ?>' class='button b'>
						
					<input type='checkbox' id='rememberMe' name='<?php echo $auth -> getData('remember') -> name; ?>' class='check b'>
					<label for='rememberMe' class='checkbox'></label>
					<label for='rememberMe'><?php echo $auth -> getData('remember') -> label; ?></label>

				</div>
			</div>
		</form>

	</div>

</body>
</html>