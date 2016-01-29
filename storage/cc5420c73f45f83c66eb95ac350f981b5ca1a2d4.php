<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Title</title>

	<link rel='icon' type='image/x-icon' href='<?php echo path; ?>img/favicon.ico'>
	<link rel='shortcut icon' type='image/x-icon' href='<?php echo path; ?>img/favicon.ico'>

	<?php foreach(TemplateEngine::getInclude("admin/style") as $k) include $k; ?>
</head>