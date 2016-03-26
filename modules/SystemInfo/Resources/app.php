<?php

	# Definition of some variables

	include __DIR__.'/routes.php';

	SystemInfoView::setNav();

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
		]
	];



?>