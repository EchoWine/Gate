<?php

class ItemView extends View{

	public static $currentObj;

	public static function setCurrentObj($o){
		self::$currentObj = $o;
	}

	public static function getCurrentObj(){
		return self::$currentObj;
	}

	/** 
	 * Initialization
	 */
	public function ini(){


	}

	/** 
	 * Set navigation menu
	 * @param int $pos position in navigation
	 */
	public function setNav($pos){
		Module::TemplateAggregate('admin/nav','admin/nav',$pos,$this -> model -> name);
	}

	/** 
	 * Set title
	 */
	public function setTitle(){
		Module::TemplateOverwrite('admin/item.main.title','admin/main.title',$this -> model -> name);
	}

	/** 
	 * Set cat
	 */
	public function setCat(){
		Module::TemplateOverwrite('admin/item.main.cat','admin/main.cat',$this -> model -> name);
	}

	public function setStyle(){
		Module::TemplateAggregate('admin/style','admin/style',1);
	}

	public function setScript(){
		Module::TemplateAggregate('admin/script','admin/script',1);
	}

	public function setPageList(){
		Module::TemplateOverwrite('admin/content','admin/main-list');
	}

	public function setPageAdd(){
		Module::TemplateOverwrite('admin/content','admin/main-add');
	}

	public function setPageEdit(){
		Module::TemplateOverwrite('admin/content','admin/main-edit');
	}

	public function setPageView(){
		Module::TemplateOverwrite('admin/content','admin/main-view');
	}

	public function setPageEmpty(){
		Module::TemplateOverwrite('admin/content','admin/main-empty');
	}

	public function setPage($action = null,$primary = null){

		$this -> setStyle();
		$this -> setScript();

		$this -> setCat();
		$this -> setTitle();

		# Check all information (data)
		$this -> controller -> check();

		switch($action){

			case $this -> controller -> getPageActionAdd():

				# Get results
				$result = $this -> controller -> getResultByPrimary($primary);


				if($primary !== null && empty($item -> results -> record)){

					# Set current page to Empty
					$this -> setPageEmpty();

				}else{
					# Set current page to Add
					$this -> setPageAdd();
				}

			break;

			case $this -> controller -> getPageActionEdit():

				# Get results
				$result = $this -> controller -> getResultByPrimary($primary);
				
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
				$result = $this -> controller -> getResultByPrimary($primary);
				
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

		return true;
	}

}

?>