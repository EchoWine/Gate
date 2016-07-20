<?php

namespace Admin\Controller;

use CoreWine\Http\Router;
use CoreWine\Http\Request as Request;
use Auth\Service\Auth;

use Api\Controller;

abstract class AdminController extends Controller{

	/**
	 * Prefix url
	 *
	 * @var string
	 */
	const PREFIX_URL = 'admin/';

	/**
	 * Prefix route
	 *
	 * @var string
	 */
	const PREFIX_ROUTE = 'admin/';


	/**
	 * Admin\Repository
	 *
	 * @var string
	 */
	public $__repository = 'Admin\Repository';

	/**
	 * View list
	 *
	 * @var array
	 */
	public $view_list = [];

	/**
	 * View add
	 *
	 * @var array
	 */
	public $view_add = [];

	/**
	 * View edit
	 *
	 * @var array
	 */
	public $view_edit = [];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_get = [];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_search = [];


	/**
	 * Set all Routers
	 */
	public function __routes(){

		parent::__routes();

		$page = $this -> url;
		$this -> route('index')
		-> url("/".AdminController::PREFIX_URL.$page)
		-> as(AdminController::PREFIX_ROUTE.$page)
		-> middleware('Admin\Middleware\Authenticate');

	}

	/**
	 * Index
	 *
	 * @return Response
	 */
	public function index(){

		return $this -> view('Admin/admin/item',[
			'table' => $this -> url,
			'api' => $this -> getFullApiURL(),
			'fields_all' => $this -> getSchemaFieldsList(),
			'fields_add' => $this -> getSchemaFieldsAdd(),
			'fields_edit' => $this -> getSchemaFieldsEdit(),
			'fields_get' => $this -> getSchemaFieldsGet(),
			'fields_search' => $this -> getSchemaFieldsSearch(),
			'sort_by_field' => $this -> getSchema() -> getSortDefaultField() -> getName(),
			'sort_by_direction' => $this -> getSchema() -> getSortDefaultDirection(),
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

	/**
	 * Get schema of fields that will be used in get
	 *
	 * @return array schema
	 */
	public function getSchemaFieldsSearch(){
		$return = [];

		foreach($this -> view_search as $name)
			$return[$name] = $this -> getSchema() -> getField($name);
		
		return $return;
	}

}
?>