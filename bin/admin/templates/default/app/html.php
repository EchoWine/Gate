<html>
c: <?php echo $element; ?>

<?php include 'head.php'; ?>

<ul>
<?php foreach((array)$user as $y){ ?>
	<li>Name: <?php echo $y['name']; ?>, Surname: <?php echo $y['surname']; ?></li>
<?php } ?>
</ul>
</html>