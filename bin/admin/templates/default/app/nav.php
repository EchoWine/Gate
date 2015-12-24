<li data-menu='dashboard'>
	<a class='href r' href='index.php'>
		<span class='fa fa-home icon'></span>
		<span>Dashboard</span>
	</a>
</li>

<!--
<li data-menu='1' data-n='3'>
	<a>
		<span class='fa fa-bar-chart icon'></span>
		<span>Section 1</span>
		<span class='active ls fa fa-angle-down'></span>
		<span class='visible inactive ls fa fa-angle-left'></span>
	</a>
	<ul>
		<li id='1'>
			<a href='#'>
				<span class='fa fa-bar-chart'></span>
				Value 1
			</a> 
		</li>
		<li id='1'>
			<a href='#'>
				<span class='fa fa-archive'></span>
				Value 2
			</a>
		</li>
		<li id='1'>
			<a href='#'>
				<span class='fa fa-bar-chart'></span>
				Value 3
			</a>
		</li>
	</ul>
</li>
-->

<?php foreach((array)$nav as $element){ ?>

	<li data-menu='<?php echo $element['name']; ?>'>
		<a class='href r' href='<?php echo $element['url']; ?>'>
			<span class='fa fa-home icon'></span>
			<span><?php echo $element['label']; ?></span>
		</a>
	</li>

<?php } ?>