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

	/**
	 * Call
	 *
	 * @param string $method
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function __call($method, $arguments){


		$isClass = substr($method,0,7);

		if($isClass === 'isClass'){
			$nameClass = __NAMESPACE__."\\".substr($method,7,strlen($method)-1)."Field";
			return $this instanceof $nameClass;
		}
		
		parent::__call($method,$arguments);
		
	}
}

?>