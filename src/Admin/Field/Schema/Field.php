<?php

namespace Admin\Field\Schema;

class Field extends \Item\Field\Schema\Field{
	
	/**
	 * Entity
	 */
	public $__entity = 'Admin\Field\Entity';

	/**
	 * View List
	 */
	public $viewAll = false;

	/**
	 * View add
	 */
	public $viewAdd = true;

	/**
	 * View edit
	 */
	public $viewEdit = true;

	/**
	 * View edit
	 */
	public $viewGet = true;

	/**
	 * View in list of all 
	 */
	public function isViewAll(){
		return $this -> viewAll;
	}

	/**
	 * View in list of all 
	 */
	public function isViewAdd(){
		return $this -> viewAdd;
	}

	/**
	 * View in list of all 
	 */
	public function isViewEdit(){
		return $this -> viewEdit;
	}

	/**
	 * View in list of all 
	 */
	public function isViewGet(){
		return $this -> viewGet;
	}
}

?>