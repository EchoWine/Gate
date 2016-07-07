<?php

namespace Basic\Controller;

use Admin\Controller\AdminController;


class UserController extends AdminController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Auth\Model\User';

	public $url = 'user';


	/**
	 * Views
	 *
	 * @var
	 */
	public $view_list = ['id','username','email'];
	public $view_edit = ['username','password','email'];
	public $view_add = ['username','password','email'];
	public $view_get = ['id','username','email'];

	public function index(){
	
		return parent::index();
	}
}

?>