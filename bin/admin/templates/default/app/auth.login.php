<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<?php include 'auth.style.php'; ?>
<body>
	<div class='container'>

		<form class='form-signin' method='POST'>
			<h2 class='form-signin-heading'><?php echo $auth -> title; ?></h2>

			<label class='sr-only'><?php echo $auth -> mail -> label; ?></label>
			<input type='email' name='<?php echo $auth -> mail -> name; ?>' value='<?php echo $auth -> mail -> value; ?>' class='form-control' placeholder='Email address' required autofocus>

			<label class='sr-only'><?php echo $auth -> pass -> label; ?></label>
			<input type='password' name='<?php echo $auth -> pass -> name; ?>' value='<?php echo $auth -> mail -> value; ?>' class='form-control' placeholder='Password' required>
			
			<div class='checkbox'>
				<input type='checkbox' id='rememberme' name='<?php echo $auth -> remember -> name; ?>'>
				<label for='rememberme'>
					<?php echo $auth -> remember -> label; ?>
				</label>
			</div>
			<button class='btn btn-lg btn-primary btn-block' name='<?php echo $auth -> login -> name; ?>' type='submit'><?php echo $auth -> login -> label; ?></button>
		</form>

	</div>

</body>
</html>