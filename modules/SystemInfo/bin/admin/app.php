<?php

	# Definition of some variables

	$p = dirname(__FILE__);


	$View = new SystemInfoView();

	$page_obj = 'info';

	$View -> setNav();
	if($pageValue == $page_obj)
		$View -> setPage();

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
			'url' => 'index.php?p='.$page_obj,
		]
	];



?>