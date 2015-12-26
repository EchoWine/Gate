<?php

	# Definition of some variables

	$p = dirname(__FILE__);

	$pageSystemInfo = isset($_GET['p']) && $_GET['p'] == 'SystemInfo';


	$SystemInfo = [
		'Server_Label' => 'Server',
		'Server_Info' => SystemInfo::getServerInfo(),
		'PHP_Label' => 'PHP',
		'PHP_Info' => SystemInfo::getPHPInfo(),
		'OS_Label' => 'OS',
		'OS_Info' => SystemInfo::getOSInfo(),
		'DB_Label' => 'DB',
		'DB_Info' => SystemInfo::getDatabaseInfo(),
		'nav' => [
			'label' => 'System Info',
			'url' => 'index.php?p=SystemInfo',
		]
	];


	SystemInfoView::template($p);

?>