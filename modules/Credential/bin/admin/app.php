<?php
	

	# Initialization
	$CredentialModel = new CredentialModel();

	/*
	$CredentialModel -> setField(new Field\Username('user'));
	$CredentialModel -> setField(new Field\Password('pass'));
	$CredentialModel -> setField(new Field\Email('email'));
	*/

	$CredentialModel -> setFields([
		new Field\Username('user'),
		new Field\Password('pass'),
		new Field\Email('email')
	]);
	

	$CredentialController = new CredentialController($CredentialModel);

	$CredentialView = new CredentialView($CredentialModel,$CredentialController);

	# Checks

	echo DB::table('credential') -> countColumns();
	die();
	
	$response = $CredentialController -> check();

	$get = $CredentialModel -> getByPrimary();
	$get = $CredentialModel -> getAll();

	# Template

	$p = dirname(__FILE__);

	$pageCredential = isset($_GET['p']) && $_GET['p'] == 'Credential';

	$Credential = [
		'nav' => [
			'label' => 'Credential',
			'url' => 'index.php?p=Credential',
		]
	];

	$CredentialView -> template($p);
?>