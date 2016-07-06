<?php

namespace Admin\Controller;

use CoreWine\DataBase\DB;
use CoreWine\Router;
use CoreWine\Request as Request;
use Auth\Service\Auth;

use Api\Controller;

abstract class AdminController extends Controller{


	/**
	 * Admin\Repository
	 */
	public $__repository = 'Admin\Repository';

	const PREFIX_URL = 'admin/';

	const PREFIX_ROUTE = 'admin/';

	public $view_list = [];
	public $view_edit = [];
	public $view_add = [];
	public $view_get = [];


	/**
	 * Set all Routers
	 */
	public function __routes(){

		parent::__routes();

		$page = $this -> url;

		$this -> route('index')
		-> url("/".AdminController::PREFIX_URL.$page)
		-> as(AdminController::PREFIX_ROUTE.$page);

	}


	/**
	 * Check
	 */
	public function __check(){
		parent::__check();

		# Redirect to /login if user isn't logged
		if(Router::is(AdminController::PREFIX_ROUTE.$this -> url)){
			if(!Auth::logged())
				Request::redirect(Router::url('admin/login'));
		}

	}
	/**
	 * Index
	 */
	public function index(){

		return $this -> view('Admin/admin/item',[
			'table' => $this -> url,
			'api' => $this -> getFullApiURL(),
			'fieldsAll' => $this -> getSchemaFieldsList(),
			'fieldsAdd' => $this -> getSchemaFieldsAdd(),
			'fieldsEdit' => $this -> getSchemaFieldsEdit(),
			'fieldsGet' => $this -> getSchemaFieldsGet(),
			'sortByField' => $this -> getSchema() -> getSortDefaultField() -> getName(),
			'sortByDirection' => $this -> getSchema() -> getSortDefaultDirection(),
		]);
	}

	/**
	 * Get schema of fields that will be used in a list
	 *
	 * @return array schema
	 */
	public function getSchemaFieldsList(){
		$return = [];

		foreach($this -> view_list as $name)
			$return[$name] = $this -> getSchema() -> getField($name);
		
		
		return $return;
	}
	
	/**
	 * Get schema of fields that will be used in form add
	 *
	 * @return array schema
	 */
	public function getSchemaFieldsAdd(){
		$return = [];

		foreach($this -> view_add as $name)
			$return[$name] = $this -> getSchema() -> getField($name);
		
		return $return;
	}

	/**
	 * Get schema of fields that will be used in form edit
	 *
	 * @return array schema
	 */
	public function getSchemaFieldsEdit(){
		$return = [];

		foreach($this -> view_edit as $name)
			$return[$name] = $this -> getSchema() -> getField($name);
		
		return $return;
	}

	/**
	 * Get schema of fields that will be used in get
	 *
	 * @return array schema
	 */
	public function getSchemaFieldsGet(){
		$return = [];

		foreach($this -> view_get as $name)
			$return[$name] = $this -> getSchema() -> getField($name);
		
		return $return;
	}

}
?>