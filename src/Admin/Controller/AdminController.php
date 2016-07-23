<?php

namespace Admin\Controller;

use CoreWine\Http\Router;
use CoreWine\Http\Request as Request;
use Auth\Service\Auth;

use Admin\View\Views;

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
	 * Set views
	 */
	public function views($views){}

	/**
	 * Index
	 *
	 * @return Response
	 */
	public function index(){
		$views = new Views($this -> getSchema());
		$this -> views($views);
		
		return $this -> view('Admin/admin/item',[
			'table' => $this -> url,
			'api' => $this -> getFullApiURL(),
			'views' => $views,
			'sort_by_field' => $this -> getSchema() -> getSortDefaultField() -> getName(),
			'sort_by_direction' => $this -> getSchema() -> getSortDefaultDirection(),
		]);
	}


}
?>