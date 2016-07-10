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

	/**
	 * Url
	 *
	 * @var
	 */
	public $url = 'users';

	/**
	 * View list
	 *
	 * @var array
	 */
	public $view_list = ['id','username','email'];

	/**
	 * View add
	 *
	 * @var array
	 */
	public $view_add = ['username','password','email'];

	/**
	 * View edit
	 *
	 * @var array
	 */
	public $view_edit = ['username','password','email'];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_get = ['id','username','email'];

}

?>