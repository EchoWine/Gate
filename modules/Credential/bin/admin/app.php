<?php
	

	# Initialization
	$CredentialModel = new CredentialModel();

	/*
	$CredentialModel -> setField(new Field\Username('user'));
	$CredentialModel -> setField(new Field\Password('pass'));
	$CredentialModel -> setField(new Field\Email('email'));
	*/

	$CredentialModel -> setFields([
		new Field\ID('id'),
		new Field\Username('user'),
		new Field\Password('pass'),
		new Field\Email('email')
	]);

	$CredentialModel -> setPrimary('id');
	

	$CredentialController = new CredentialController($CredentialModel);

	$CredentialView = new CredentialView($CredentialModel,$CredentialController);

	# Category: list,get,add,edit ecc...

	$p = dirname(__FILE__);

	$cat_get = 'cat';
	$cat_list = 'list';
	$cat_add = 'add';
	$cat_edit = 'edit';
	$cat_get = 'get';
	$page_get = 'p';
	$page_obj = 'Credential';
	$label = 'Credential';
	$cat = isset($_GET[$cat_get]) ? $_GET[$cat_get] : null;

	$pageCredential = isset($_GET[$page_get]) && $_GET[$page_get] == $page_obj;
	
	$urlToAdd = '?'.$page_get.'='.$page_obj.'&'.$cat_get.'='.$cat_add;
	$urlToList = '?'.$page_get.'='.$page_obj.'&'.$cat_get.'='.$cat_list;


	# Menu
	$Credential = [
		'nav' => [
			'label' => $label,
			'url' => 'index.php?'.$page_get.'='.$page_obj,
		]
	];

	$CredentialView -> setPath($p);
	$CredentialView -> setNav();

	if(!$pageCredential)return;

	switch($cat){
		case $cat_add:
			$data = [
				'form' => [
					0 => ['label' => 'nome','form' => 'input']
				]
			];


			$CredentialView -> setAdd();
		break;
		default:

			# Checks
			$response = $CredentialController -> check();

			// $get = $CredentialModel -> getByPrimary(1);
			$result = $CredentialModel -> getAll();

			$data = [
				'columns' => $CredentialModel -> getFieldsNameInList(),
				'result' => []
			];

			$data['result'] = $result;
			



			$CredentialView -> template();
		break;
	}

?>