<?php


	# Initialization Controller
	$Controller -> ini();
	$item = $Controller;

	if($pageValue == $Controller -> nameURL){

		$View -> setCat();
		$View -> setTitle();

		# Check all information (data)
		$Controller -> check();

		switch($Controller -> getPageActionValue()){

			case $Controller -> getPageActionAdd():

				# Get results
				$result = $Controller -> getResultByPrimary();


				if($Controller -> getData('g_primary') -> value !== null && empty($item -> results -> record)){

					# Set current page to Empty
					$View -> setPageEmpty();

				}else{
					# Set current page to Add
					$View -> setPageAdd();
				}

			break;

			case $Controller -> getPageActionEdit():

				# Get results
				$result = $Controller -> getResultByPrimary();
				
				if(empty($item -> results -> record)){

					# Set current page to Empty
					$View -> setPageEmpty();
				}else{

					# Set current page to Edit
					$View -> setPageEdit();
				}
			break;

			case $Controller -> getPageActionView():

				# Get results
				$result = $Controller -> getResultByPrimary();
				
				if(empty($item -> results -> record)){

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