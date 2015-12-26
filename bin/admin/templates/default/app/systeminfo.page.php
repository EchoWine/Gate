<section class="content-header">
	<h1>
		System info
		<small>Basic information</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
		<li class="active">Here</li>
	</ol>
</section>

<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Basic information</h3>
		</div>
		<div class="box-body">
			<table class="table table-bordered">
				<tr>
					<td><?php echo $SystemInfo['OS_Label']; ?></td>
					<td><?php echo $SystemInfo['OS_Info']; ?></td>
				</tr>
				<tr>
					<td><?php echo $SystemInfo['Server_Label']; ?></td>
					<td><?php echo $SystemInfo['Server_Info']; ?></td>
				</tr>
				<tr>
					<td><?php echo $SystemInfo['PHP_Label']; ?></td>
					<td><?php echo $SystemInfo['PHP_Info']; ?></td>
				</tr>
				<tr>
					<td><?php echo $SystemInfo['DB_Label']; ?></td>
					<td><?php echo $SystemInfo['DB_Info']; ?></td>
				</tr>
			</table>
		</div>
	</div>
</section>