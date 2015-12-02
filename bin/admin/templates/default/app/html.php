<html>
c: {{element}}

c: {{wr}}
<?php include 'head.php';?>

<ul>
{<?php foreach((array)$user as $y){ ?>}
	<li>Name: {{y.name}}, Surname: {{y.surname}}</li>
<?php } ?>
</ul>
</html>