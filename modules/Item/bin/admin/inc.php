<?php

	$Controller -> setNameURL($page_obj);

	# Initialization Controller
	$Controller -> ini();
	$item = $Controller;

	if($pageValue == $page_obj){

		$View -> setCat();
		$View -> setTitle();

		# Check all information (data)
		$Controller -> check();

		switch($Controller -> getPageActionValue()){
			case $Controller -> getPageActionAdd():

				# Set current page to Add
				$View -> setPageAdd();
			break;
			case $Controller -> getPageActionEdit():

				# Get results for list
				$result = $Controller -> getResultByPrimary();
				
				if(empty($result -> record)){

					# Set current page to Empty
					$View -> setPageEmpty();
				}else{

					# Set current page to Edit
					$View -> setPageEdit();
				}
			break;
			case $Controller -> getPageActionView():

				# Get results for list
				$result = $Controller -> getResultByPrimary();
				
				if(empty($result -> record)){

					# Set current page to Empty
					$View -> setPageEmpty();
				}else{

					# Set current page to Edit
					$View -> setPageView();
				}
			break;
			default:
				# Get results for list
				$results = $Controller -> getResults();

				# Set current page to List
				$View -> setPageList();
			break;
		}
	}


	
?>