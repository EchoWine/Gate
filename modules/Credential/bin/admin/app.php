<?php

	# Definition of some variables

	$p = dirname(__FILE__);

	CredentialView::template($p);

	$pageCredential = isset($_GET['p']) && $_GET['p'] == 'Credential';

	$Credential = [
		'nav' => [
			'label' => 'Credential',
			'url' => 'index.php?p=Credential',
		]
	];
?>