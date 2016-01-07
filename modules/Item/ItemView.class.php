<?php

class ItemView extends View{
	
	/** 
	 * Initialization
	 */
	public function ini(){
		$this -> setStyle();
		$this -> setScript();
	}
	
	public function setStyle(){
		Module::TemplateAggregate('style','style',1);
	}

	public function setScript(){
		Module::TemplateAggregate('script','script',1);
	}

	public function setPageList(){
		Module::TemplateOverwrite('content','main-list');
	}

	public function setPageAdd(){
		Module::TemplateOverwrite('content','main-add');
	}

	public function setPageEdit(){
		Module::TemplateOverwrite('content','main-edit');
	}

	public function setPageView(){
		Module::TemplateOverwrite('content','main-view');
	}

	public function setPageEmpty(){
		Module::TemplateOverwrite('content','main-empty');
	}

	public function setPage($page){

		if($page == $this -> controller -> nameURL){

			$this -> setCat();
			$this -> setTitle();

			# Check all information (data)
			$this -> controller -> check();

			switch($this -> controller -> getPageActionValue()){

				case $this -> controller -> getPageActionAdd():

					# Get results
					$result = $this -> controller -> getResultByPrimary();


					if($this -> controller -> getData('g_primary') -> value !== null && empty($item -> results -> record)){

						# Set current page to Empty
						$this -> setPageEmpty();

					}else{
						# Set current page to Add
						$this -> setPageAdd();
					}

				break;

				case $this -> controller -> getPageActionEdit():

					# Get results
					$result = $this -> controller -> getResultByPrimary();
					
					if(empty($this -> controller -> results -> record)){

						# Set current page to Empty
						$this -> setPageEmpty();
					}else{

						# Set current page to Edit
						$this -> setPageEdit();
					}
				break;

				case $this -> controller -> getPageActionView():

					# Get results
					$result = $this -> controller -> getResultByPrimary();
					
					if(empty($this -> controller -> results -> record)){

						# Set current page to Empty
						$this -> setPageEmpty();
					}else{

						# Set current page to Edit
						$this -> setPageView();
					}
				break;

				default:
					# Get results for list
					$results = $this -> controller -> getResults();

					# Set current page to List
					$this -> setPageList();
				break;
			}
		}
	}

}

?>