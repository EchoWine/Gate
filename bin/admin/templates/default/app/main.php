<!DOCTYPE html>
<html>
<?php include 'head.php'; ?>
<body>

	<!--
	<ul>
	<?php foreach((array)$user as $y){ ?>
		<li>Name: <?php echo $y['name']; ?>, Surname: <?php echo $y['surname']; ?></li>
	<?php } ?>
	</ul>
	-->

	<?php if(!$logged){ ?>	<?php include 'auth.login.php'; ?><?php }else{ ?>	<?php include 'container.php'; ?><?php } ?>
	<?php include 'script.php'; ?>
</body>
</html>